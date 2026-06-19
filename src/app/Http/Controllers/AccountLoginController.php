<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountLoginController extends Controller
{
    // ログイン画面表示
    public function showLogin()
    {
        return view('login');
    }

    // ログイン処理
    public function login(Request $request)
    {
        // バリデーション
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスを正しく入力してください。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home')->with('success', 'ログインに成功しました！');
        }

        // 失敗
        return back()->withErrors([
            'login' => 'メールアドレスまたはパスワードが違います。',
        ])->withInput();
    }

    // ログアウト
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
