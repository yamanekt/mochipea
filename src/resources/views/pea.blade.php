<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ペア設定</title>
  <link rel="stylesheet" href="{{ asset('css/pea.css') }}">
</head>
<body>
  <p> </p>
  <div class="bg"></div>
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
</body>
</html>
