<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountLoginController extends Controller
{
    // 登録画面表示
    public function showRegister()
    {
        return view('register');
    }

    // 登録処理
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^[a-zA-Z0-9]+$/'
            ],
        ], [
            'name.required' => '名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスを正しく入力してください。',
            'email.unique' => 'このメールアドレスはすでに登録されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
            'password.min' => 'パスワードは8文字以上にしてください。',
            'password.regex' => 'パスワードは英数字で入力してください。',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'アカウント作成に成功しました！');
    }

    // ログイン画面表示
    public function showLogin()
    {
        return view('login');
    }

    // ログイン処理
    public function login(Request $request)
    {
        $result = [
            "status" => true,
            "message" => null,
            "result" => false,
        ];

        // POSTデータ取得
        $email = $request->input('email');
        $password = $request->input('password');

        // メールチェック
        if ($email === null || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result["status"] = false;
            $result["message"] = "メールアドレスを正しく入力してください。";
        }

        // パスワードチェック
        if ($result["status"]) {
            if ($password === null || $password === "") {
                $result["status"] = false;
                $result["message"] = "パスワードを入力してください。";
            }
        }

        // ログイン処理
        if ($result["status"]) {
            try {
                // 入力されたメールアドレスのユーザーを探す
                $user = User::where('email', $email)->first();

                // ユーザーが存在しない
                if ($user === null) {
                    $result["status"] = false;
                    $result["message"] = "メールアドレスまたはパスワードが違います。";

                    // パスワードが違う
                } elseif (!Hash::check($password, $user->password)) {
                    $result["status"] = false;
                    $result["message"] = "メールアドレスまたはパスワードが違います。";

                    // ログイン成功
                } else {
                    $request->session()->regenerate();

                    session([
                        "user_id" => $user->id,
                        "user_name" => $user->name,
                        "user_email" => $user->email,
                    ]);

                    $result["result"] = true;
                    $result["message"] = "ログインに成功しました！";

                    return redirect('/')
                        ->with('success', $result["message"]);
                }

            } catch (\Exception $e) {
                $result["status"] = false;
                $result["message"] = "DBエラー：" . $e->getMessage();
            }
        }

        return back()
            ->withErrors([
                'login' => $result["message"],
            ])
            ->withInput();
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        $request->session()->forget([
            'user_id',
            'user_name',
            'user_email',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
