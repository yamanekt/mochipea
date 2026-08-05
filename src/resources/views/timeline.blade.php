<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>タイムライン</title>
  @vite(['resources/css/common.css','resources/css/goals.css'])
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

  {{-- 案内キャラクター --}}
  <div class="guide-character">
    <img src="{{ asset('images/IMG_0639.png') }}" alt="案内キャラクター">
  </div>

  {{-- 達成入力（コメント）を新しい順に並べたタイムライン --}}
  <div class="goal-list">
    @forelse ($entries as $entry)
      <div class="goal-card">
        {{-- 目標名 --}}
        <p class="goal-title">目標名：{{ $entry->title }}</p>

        {{-- 投稿者名 ・ 何日前か（Carbonのロケールはapp.phpでjaに設定済みなので「3日前」と表示される） --}}
        <p class="entry-meta">
          {{ $entry->user_name }}・{{ \Carbon\Carbon::parse($entry->created_at)->diffForHumans() }}
        </p>

        {{-- 今回の回数（達成数）＋単位 --}}
        <p class="entry-count">回数：{{ $entry->value }}{{ $entry->unit }}</p>

        {{-- コメント（memoが未入力ならNULLなので代わりの文言を出す） --}}
        <p class="entry-comment">
          {{ $entry->memo ?? '（コメントなし）' }}
        </p>

        {{-- その目標の詳細（現在状況）画面へ --}}
        <a href="{{ route('situation.show', $entry->goal_id) }}" class="detail-btn">詳細を見る</a>
      </div>
    @empty
      <div class="goal-card">
        <p>まだ達成入力がありません。</p>
      </div>
    @endforelse
  </div>

  <nav class="bottom-nav">
    <a href="{{ url('/home') }}" class="nav-item">
      <i class="fa-solid fa-house"></i><span>ホーム</span>
    </a>
    <a href="{{ route('current-goals') }}" class="nav-item">
      <i class="fa-solid fa-list-check"></i><span>目標一覧</span>
    </a>
    <a href="{{ url('/pea') }}" class="nav-item">
      <i class="fa-solid fa-circle-plus"></i><span>目標作成</span>
    </a>
    <a href="{{ url('/timeline') }}" class="nav-item active" aria-current="page">
      <i class="fa-solid fa-clock-rotate-left"></i><span>タイムライン</span>
    </a>
    <a href="{{ route('mypage') }}" class="nav-item">
      <i class="fa-regular fa-user"></i><span>マイページ</span>
    </a>
  </nav>

</body>
</html>
