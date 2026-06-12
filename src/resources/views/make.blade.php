<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ルーム作成</title>
  <link rel="stylesheet" href="{{ asset('css/make.css') }}">
</head>
<body>
  <div class="bg"></div>
  <div class="container">
    <h1>ルーム作成</h1>
    <h2>パスワード設定</h2>

    <label>4桁のパスワード *</label>

    <div class="password-area">
      <input type="text" id="roomNumber" name="roomNumber" readonly required>
      <button type="button" class="generate-btn" onclick="generatePassword()">自動生成</button>
    </div>

    {{-- <a href="{{ url('/goals') }}" class="main-btn">ルーム作成</a> --}}
    <form method="POST" action="/create-room">
    @csrf

    <input type="hidden" id="roomNumber" name="roomNumber">

    <button type="submit" class="main-btn">
        ルーム作成
    </button>
</form>
  </div>

  <script src="{{ asset('js/make.js') }}"></script>
</body>
</html>
