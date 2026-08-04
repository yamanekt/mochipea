<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ホーム</title>
  @vite(['resources/css/home.css'])
  @vite(['resources/css/common.css'])
  <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    {{-- ヘッター --}}
<header class="top-header">



    <!-- 中央 -->
    <h1 class="logo">MochiPea</h1>

    <!-- 右 -->
    <div class="header-right">

       <a href="#" class="icon-btn notice">
    <i class="fa-regular fa-bell"></i>
    <small>お知らせ</small>
        </a>

    </div>

</header>


<!-- ✅ オープニング演出 -->
<div class="intro">
  <img src="{{ asset('images/IMG_0639.png') }}" alt="キャラ">
</div>

{{-- <form id="logout-form" action="{{ route('logout') }}" method="POST">
  @csrf
  <button type="submit" class="logout-btn">ログアウト</button>
</form> --}}
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
{{-- フッター --}}
<nav class="bottom-nav">

    <a href="{{ url('/home') }}" class="nav-item active">
        <i class="fa-solid fa-house"></i>
        <span>ホーム</span>
    </a>

  <a href="{{ route('current-goals') }}" class="nav-item">
    <i class="fa-solid fa-list-check"></i>
    <span>目標一覧</span>
</a>

    <a href="{{ url('/pea') }}" class="nav-item">
    <i class="fa-solid fa-circle-plus"></i>
    <span>目標作成</span>
    </a>

    <a href="{{ url('/timeline') }}" class="nav-item">
    <i class="fa-solid fa-clock-rotate-left"></i>
    <span>タイムライン</span>
    </a>

    <a href="{{ url('/mypage') }}" class="nav-item">
        <i class="fa-regular fa-user"></i>
        <span>マイページ</span>
    </a>

</nav>
</html>
