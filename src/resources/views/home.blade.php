<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ホーム</title>
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

<!-- ✅ オープニング演出 -->
<div class="intro">
  <img src="{{ asset('images/IMG_0639.png') }}" alt="キャラ">
</div>

<div class="bg"></div>
<a href="{{ url('/login') }}" class="logout-btn">ログアウト</a>
<div class="container">

  <a href="{{ url('/current-goals') }}" class="box">
    <h1>進行中の目標</h1>
    <p>現在進行中のものや過去のデータを閲覧できます</p>
  </a>

  <a href="{{ url('/pea') }}" class="box">
    <h1>新しい目標</h1>
    <p>新しく目標を設定します</p>
  </a>

</div>

</body>
</html>
