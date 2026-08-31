<?php

// 新規ユーザー登録

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\WelcomeMail;               // 登録完了メール
use App\Models\User;                    // Userモデル（usersテーブルを操作するクラス）
use Illuminate\Support\Facades\Hash;    // パスワードをハッシュ化（暗号化）するクラス
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AccountRegisterController extends Controller
{
    /**
     * 新規登録画面を表示する
     * Route: GET /register
     */
    public function showRegister()
    {
        return view('register'); // resources/views/register.blade.php を表示
    }

    /**
     * 新規登録処理（フォーム送信時に呼ばれる）
     * Route: POST /register
     */
    public function register(Request $request)
    {
        // ─バリデーション（入力チェック）
        // required: 必須  string: 文字列  max:255: 最大255文字
        // email: メール形式  unique:users,email: usersテーブルのemailカラムに同じ値がないか
        // confirmed: password_confirmation フィールドと一致するか
        // min:8: 8文字以上  regex: 正規表現（英数字のみ許可）
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',     // ← Blade側に password_confirmation という名前のフィールドが必要
                'min:8',
                'regex:/^[a-zA-Z0-9]+$/'  // 英数字のみ
            ],
        ], [
            // エラーメッセージの日本語カスタマイズ
            'name.required' => '名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスを正しく入力してください。',
            'email.unique' => 'このメールアドレスはすでに登録されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
            'password.min' => 'パスワードは8文字以上にしてください。',
            'password.regex' => 'パスワードは英数字で入力してください。',
        ]);

        // ユーザーをDBに保存
        // User::create() → usersテーブルに1行INSERTする
        // Hash::make() → パスワードを平文のまま保存しない（セキュリティの基本！）
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 登録完了メール。
        // 送信に失敗しても登録自体は成功しているので、例外で画面を落とさず記録だけ残す
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Throwable $e) {
            Log::warning('登録完了メールの送信に失敗しました', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        // ログイン画面にリダイレクト
        // with('success', '...') → セッションに成功メッセージを入れて渡す（Blade側で表示）
        return redirect()
            ->route('login')
            ->with('success', 'アカウント作成に成功しました！');
    }
}
