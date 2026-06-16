<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>

    <div class="container">
        <h1>新規登録</h1>
        <h2></h2>

<form action="{{ url('/register') }}" method="post">
    @csrf

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red">{{ $error }}</li>
            @endforeach
        </ul>
    @endif
<label for="name">ユーザー名 *</label>
<input type="text" id="name" name="name" autocomplete="username" required>

<label for="email">Eメール *</label>
<input type="email" id="email" name="email" autocomplete="email" required>

<label for="password">パスワード *</label>
<input type="password" id="password" name="password" autocomplete="new-password" required>

<label for="password_confirmation">パスワード確認 *</label>
<input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
    <button type="submit">アカウント作成</button>
</form>
    </div>

</body>

</html>


