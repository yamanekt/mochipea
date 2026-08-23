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

<form id="logout-form" action="{{ route('logout') }}" method="POST">
  @csrf
  <button type="submit" class="logout-btn">ログアウト</button>
</form>
<div class="bg"></div>
<div class="container">

<<<<<<< HEAD
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
=======
@section('content')
    <div class="page-bg"></div>
    <main class="page home-page">
        <section class="home-hero"><div><p class="eyebrow">もちぺあへようこそ</p><h1>今日も、<br>いっしょにがんばろう。</h1><p>目標をペアと共有して、楽しく続けよう。</p></div><img src="{{ asset('images/IMG_0639.png') }}" alt=""></section>
        <nav class="home-menu" aria-label="メインメニュー">
            <a href="{{ route('current-goals') }}" class="home-menu-card"><span class="home-menu-icon">▤</span><span><strong>目標一覧</strong><small>いま取り組んでいる目標を見る</small></span><b>›</b></a>
            <a href="{{ route('goals') }}" class="home-menu-card"><span class="home-menu-icon plus">＋</span><span><strong>目標をつくる</strong><small>新しい目標をペアと始める</small></span><b>›</b></a>
            <a href="{{ route('timeline') }}" class="home-menu-card"><span class="home-menu-icon">♡</span><span><strong>タイムライン</strong><small>みんなのがんばりを見る</small></span><b>›</b></a>
        </nav>
    </main>
@endsection
>>>>>>> 7e89c7a (見た目を変更)
