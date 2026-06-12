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
    @csrf
    <label>ユーザー名 *</label>
    <input type="text" name="name" required>

    <label>Eメール *</label>
    <input type="email" name="email" required>

    <label>パスワード *</label>
    <input type="password" name="password" required>

    <a href="{{ url('/login') }}">アカウント作成</a>
  </form>
</div>

</body>
</html>
