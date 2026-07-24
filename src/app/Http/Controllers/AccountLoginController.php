<?php

// ログイン・ログアウト

namespace App\Http\Controllers;

use Illuminate\Http\Request;           // ユーザーからのリクエスト情報（フォーム入力値など）を扱うクラス
use Illuminate\Support\Facades\Auth;   // Laravel標準の認証（ログイン/ログアウト）機能
use Illuminate\Support\Facades\Cookie;

class AccountLoginController extends Controller
{
    /**
     * ログイン画面を表示する
     * Route: GET /login
     */
    public function showLogin()
    {
        return view('login'); // resources/views/login.blade.php を表示
    }

    /**
     * ログイン処理（フォーム送信時に呼ばれる）
     * Route: POST /login
     */
    public function login(Request $request)
    {
        // ─バリデーション（入力チェック）
        // validate() で入力値をチェック。ルールに違反するとエラーメッセージを返して前の画面に戻る。
        // required: 必須入力  email: メール形式かチェック
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            // 第2引数: エラーメッセージを日本語でカスタマイズ
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスを正しく入力してください。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        // ログイン試行
        // Auth::attempt() → メールアドレスとパスワードがDBと一致するかチェック
        // 一致すればログイン状態にして true を返す
        if (Auth::attempt($credentials)) {
            // セッションIDを再生成（セキュリティ対策：セッション固定攻撃を防ぐ）
            $request->session()->regenerate();

            // intended() → ログイン前にアクセスしようとしていたURLにリダイレクト（なければ/home）
            return redirect()->intended('/home')->with('success', 'ログインに成功しました！');
        }

        // ログイン失敗
        // back() → 前の画面（ログイン画面）に戻る
        // withErrors() → エラーメッセージをBladeに渡す
        // withInput() → 入力値を保持（メールアドレスが消えないようにする）
        return back()->withErrors([
            'login' => 'メールアドレスまたはパスワードが違います。',
        ])->withInput();
    }

    /**
     * ログアウト処理
     * Route: POST /logout
     */
    public function logout(Request $request)
    {
        Auth::logout();                          // ログアウト（認証情報をクリア）
        $request->session()->invalidate();       // セッションを無効化（全データ削除）
        $request->session()->regenerateToken();   // CSRFトークンを再生成（セキュリティ対策）

        // ブラウザに保存されているセッションCookieも期限切れにする
        Cookie::queue(Cookie::forget(
            config('session.cookie'),
            config('session.path'),
            config('session.domain')
        ));

        return redirect()->route('login');        // ログイン画面にリダイレクト
    }
}
