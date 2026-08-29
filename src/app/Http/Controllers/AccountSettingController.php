<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * マイページから開くアカウント設定（名前・メール・パスワード・退会）。
 */
class AccountSettingController extends Controller
{
    public function editProfile()
    {
        return view('account.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'name.required'  => '名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email'    => 'メールアドレスを正しく入力してください。',
            'email.unique'   => 'このメールアドレスはすでに使われています。',
        ]);

        $user->update($validated);

        return redirect()->route('mypage')->with('success', 'アカウント情報を更新しました。');
    }

    public function editPassword()
    {
        return view('account.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', 'min:8', 'regex:/^[a-zA-Z0-9]+$/'],
        ], [
            'current_password.required' => '現在のパスワードを入力してください。',
            'password.required'         => '新しいパスワードを入力してください。',
            'password.confirmed'        => '新しいパスワードが一致しません。',
            'password.min'              => 'パスワードは8文字以上にしてください。',
            'password.regex'            => 'パスワードは英数字で入力してください。',
        ]);

        $user = Auth::user();

        // 本人確認。現在のパスワードが違えば変更させない
        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => '現在のパスワードが違います。']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        // パスワードを変えたら他端末のセッションを無効にする
        $request->session()->regenerate();

        return redirect()->route('mypage')->with('success', 'パスワードを変更しました。');
    }

    /**
     * 削除を受け付けるかどうか。デモ期間中は config/demo.php で止める。
     */
    private function deletionEnabled(): bool
    {
        return (bool) config('demo.account_deletion_enabled', true);
    }

    public function confirmDelete()
    {
        return view('account.delete', [
            'deletionEnabled' => $this->deletionEnabled(),
            'notice' => config('demo.account_deletion_notice'),
        ]);
    }

    /**
     * 退会。ペアや目標は外部キーの cascade で一緒に消える。
     */
    public function destroy(Request $request)
    {
        // 画面を隠すだけでは直接POSTされると通ってしまうので、ここでも止める
        if (! $this->deletionEnabled()) {
            return redirect()->route('account.delete')
                ->withErrors(['password' => config('demo.account_deletion_notice')]);
        }

        $request->validate(
            ['password' => ['required']],
            ['password.required' => 'パスワードを入力してください。']
        );

        $user = Auth::user();

        if (! Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'パスワードが違います。']);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('login')->with('success', 'アカウントを削除しました。');
    }
}
