<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>

    <div class="container">
        <h1>新規登録</h1>
        <h2>アカウント作成</h2>

        <form action="{{ url('/register') }}" method="post">
            @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color:red">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ url('/register') }}" method="get">
                @csrf
                <label>ユーザー名 *</label>
                <input type="text" name="name" required>

                <label>Eメール *</label>
                <input type="email" name="email" required>

                <label>パスワード *</label>
                <input type="password" name="password" required>

                <label>パスワード確認 *</label>
                <input type="password" name="password_confirmation" required>

                <button type="submit" class="main-btn">アカウント作成</button>
            </form>
    </div>

</body>

</html>
