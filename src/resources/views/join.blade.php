<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ルーム入室</title>
  <link rel="stylesheet" href="{{ asset('css/make.css') }}">
</head>
<body>
  <div class="bg"></div>
  <div class="container">
    <h1>ルーム入室</h1>
    <h2>部屋を探す</h2>

    <form action="{{ url('/goals') }}" method="post">
      @csrf
      <label>4桁の部屋番号 *</label>
      <input type="text" name="code" minlength="4" maxlength="4" required>
      <button type="submit" class="main-btn">ルーム入室</button>
    </form>
  </div>
</body>
</html>
