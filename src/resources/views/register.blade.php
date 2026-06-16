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

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> login
        <form action="{{ url('/register') }}" method="post">
            @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
<<<<<<< HEAD
                        <li>{{ $error }}</li>
=======
                        <li style="color:red">{{ $error }}</li>
>>>>>>> login
                    @endforeach
                </ul>
            @endif
            @csrf
            <label>ユーザー名 *</label>
            <input type="text" name="name" required>
<<<<<<< HEAD
=======
  <form action="{{ url('/register') }}" method="get">
    @csrf
    <label>ユーザー名 *</label>
    <input type="text" name="name" required>
>>>>>>> e2ac57d6a87cd1bdf46c9d7e69d62492c551e7b6
=======
>>>>>>> login

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
