<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    @vite(['resources/css/common.css', 'resources/css/login.css'])
</head>

<body>

    <div class="bg"></div>

    <div class="page">

        <div class="container panel">

            <img src="{{ asset('images/welcome2.png') }}" alt="ロゴ" class="top-image">

            <h1>Welcome Back!</h1>
            <h2>サインイン</h2>

            <!-- フォーム全体を囲む -->
            <div class="form-area">

                <form id="loginForm" action="{{ url('/login') }}" method="post">
                    @csrf

                    <div class="input-group">
                        <label for="userId">Eメール</label>
                        <input type="email" id="userId" name="email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">パスワード</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <p class="error">{{ $errors->first('login') }}</p>

                    <button type="submit">ログイン</button>
                </form>


            </div>

            <div class="register-link">
                <a href="{{ url('/register') }}" class="register-btn">
                    新規登録
                </a>
                <!-- 👇 キャラクター画像 -->
                <img src="{{ asset('images/IMG_0639.png') }}" alt="キャラ" class="point-char">

            </div>

        </div>

    </div>

</body>

</html>
