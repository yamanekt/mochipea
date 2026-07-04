<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>

<div class="bg"></div>

<div class="page">

    <div class="container">

        <a href="{{ url('/login') }}" class="back-btn">戻る</a>

        <img src="{{ asset('images/welcome2.png') }}" alt="welcome" class="top-image">

        <h1>Welcome!</h1>
        <div class="sub-title">新規登録</div>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color:red">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <!-- 👇 フォームエリア -->
        <div class="form-area">

            <form action="{{ url('/register') }}" method="post">
                @csrf

                <label>ユーザー名 *</label>
                <input type="text" name="name" required>

                <label>Eメール *</label>
                <input type="email" name="email" required>

                <label>パスワード *</label>
                <input type="password" name="password" required>

                <label>パスワード確認 *</label>
                <input type="password" name="password_confirmation" required>

                <button type="submit">
                    アカウント作成
                </button>
            </form>

            <!-- 👇 キャラ（ボタンを指す） -->
            <img src="{{ asset('images/IMG_0639.png') }}" class="point-char">

        </div>

    </div>

</div>

</body>
</html>
