<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ペア設定</title>
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('css/all.css') }}?v={{ filemtime(public_path('css/all.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/pea.css') }}?v={{ filemtime(public_path('css/pea.css')) }}">
</head>

<body>

  <div class="bg"></div>

<div class="rolling-area">
       <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r1">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r2">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r3">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r4">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r5">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r6">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r7">
</div>

<a href="{{ url('/home') }}" class="back-btn">戻る</a>

<div class="container">

        <a href="{{ url('/goals') }}" class="box">
            <h1>部屋を作る</h1>
            <p>表示された部屋番号を相手に伝えてください</p>
        </a>

        <a href="{{ url('/join') }}" class="box">
            <h1>部屋を探す</h1>
            <p>部屋番号を教えてもらってください</p>
        </a>

    </div>

        <script src="{{ asset('js/pea.js') }}"></script>
</body>
</html>
