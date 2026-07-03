<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ルーム入室</title>
  <link rel="stylesheet" href="{{ asset('css/make.css') }}">
</head>
<body>
  <div class="bg">

<div class="rolling-area">

    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r1">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r2">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r3">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r4">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r5">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r6">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r7">

</div>

  </div>
  <div class="container">
    <h1>ルーム入室</h1>
    <h2>部屋を探す</h2>

    <form action="{{ url('/pair-code-check') }}" method="POST">
      @csrf
      <label>4桁の部屋番号 *</label>
      <input type="text" name="code" minlength="4" maxlength="4" required>
      <button type="submit" class="main-btn">ルーム入室</button>
    </form>
  </div>
</body>
</html>
