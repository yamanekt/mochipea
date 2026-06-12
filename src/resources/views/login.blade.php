<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="container">
  <h2>サインイン</h2>

  <form id="loginForm" action="{{ url('/home') }}" method="get">
    @csrf
    <div class="input-group">
      <label for="userId">Eメール</label>
      <input type="email" id="userId" name="email" required>
    </div>

    <div class="input-group">
      <label for="password">パスワード</label>
      <input type="password" id="password" name="password" required>
    </div>

    <p class="error" id="errorMsg"></p>

    <button type="submit">ログイン</button>
  </form>

  <p class="register-link">
    初めての方はこちら
    <a href="{{ url('/register') }}">新規登録</a>
  </p>
</div>

</body>
</html>
