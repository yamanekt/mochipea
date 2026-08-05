<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ペア設定</title>
  @vite(['resources/css/common.css','resources/css/pea.css'])
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
  <header class="top-header">
    <h1 class="logo">もちぺあ</h1>
    <div class="header-right">
      <a href="#" class="icon-btn notice">
        <i class="fa-regular fa-bell"></i>
        <small>お知らせ</small>
      </a>
    </div>
  </header>

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

  <nav class="bottom-nav">
    <a href="{{ url('/home') }}" class="nav-item">
      <i class="fa-solid fa-house"></i><span>ホーム</span>
    </a>
    <a href="{{ route('current-goals') }}" class="nav-item">
      <i class="fa-solid fa-list-check"></i><span>目標一覧</span>
    </a>
    <a href="{{ url('/pea') }}" class="nav-item active" aria-current="page">
      <i class="fa-solid fa-circle-plus"></i><span>目標作成</span>
    </a>
    <a href="{{ url('/timeline') }}" class="nav-item">
      <i class="fa-solid fa-clock-rotate-left"></i><span>タイムライン</span>
    </a>
    <a href="{{ route('mypage') }}" class="nav-item">
      <i class="fa-regular fa-user"></i><span>マイページ</span>
    </a>
  </nav>
</body>
</html>
