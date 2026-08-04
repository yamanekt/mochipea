<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ホーム</title>
  @vite(['resources/css/home.css'])
</head>
<body>

<!-- ✅ オープニング演出 -->
<div class="intro">
  <img src="{{ asset('images/IMG_0639.png') }}" alt="キャラ">
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST">
  @csrf
  <button type="submit" class="logout-btn">ログアウト</button>
</form>
<div class="bg"></div>
<div class="container">

  <a href="{{ url('/timeline') }}" class="box">
    <h1>タイムライン</h1>
    <p>目標の一覧を確認できます</p>
  </a>

  <a href="{{ url('/pea') }}" class="box">
    <h1>新しい目標</h1>
    <p>新しく目標を設定します</p>
  </a>

</div>
  <script src="{{ asset('js/home.js') }}?v={{ filemtime(public_path('js/home.js')) }}"></script>
</body>
</html>
