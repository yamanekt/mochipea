<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>進行中の目標</title>
  @vite(['resources/css/goals.css'])
  @vite(['resources/css/common.css'])
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

<div class="page-content">
    <form method="GET" action="{{ route('current-goals') }}" class="sort-form">

        <label class="sort-option">
            <input
                type="radio"
                name="sort"
                value="updated"
                {{ $sort == 'updated' ? 'checked' : '' }}
                onchange="this.form.submit()">
            更新順
        </label>

        <label class="sort-option">
            <input
                type="radio"
                name="sort"
                value="created"
                {{ $sort == 'created' ? 'checked' : '' }}
                onchange="this.form.submit()">
            登録順
        </label>

    </form>

 <div class="bg"></div>
<div class="guide-character">
    <img src="{{ asset('images/IMG_0639.png') }}" alt="案内キャラクター">
</div>

<div class="goal-list">

    @forelse ($goals as $goal)
      @php
        $progressRate = min(100, max(0, $goal->progress_rate));
        $isExpired = $goal->display_status === '期限切れ';
      @endphp

      <div class="goal-card">
        <div class="goal-card-header">
          <p class="goal-title">目標名：{{ $goal->title }}</p>
          <span class="status-badge {{ $isExpired ? 'status-expired' : 'status-active' }}">
            <span class="status-dot"></span>
            {{ $goal->display_status }}
          </span>
        </div>

    <p>
        カテゴリ：
        @if ($goal->category === 'exercise')
            運動
        @elseif ($goal->category === 'study')
            勉強
        @elseif ($goal->category === 'game')
            ゲーム
        @else
            {{ $goal->category }}
        @endif
    </p>

    <p>ペア相手：{{ $goal->partner_name }}</p>

    <p>
        進捗：
        {{ $goal->current_value }}
        /
        {{ $goal->target_value }}
        {{ $goal->unit }}
    </p>

        <div class="progress-block">
          <div class="progress-label">
            <span>達成率</span>
            <strong>{{ $goal->progress_rate }}%</strong>
          </div>
          <div class="progress-track" aria-label="達成率 {{ $goal->progress_rate }}%">
            <div class="progress-fill" style="width: {{ $progressRate }}%;"></div>
          </div>
        </div>

        <p>
          期限：
          {{ \Carbon\Carbon::parse($goal->deadline)->format('Y/m/d') }}
        </p>

        <a href="{{ route('situation.show', $goal->id) }}" class="detail-btn">詳細を見る</a>
      </div>
    @empty
      <div class="goal-card">
        <p>現在登録されている目標はありません。</p>
      </div>
    @endforelse
  </div>
</div>

<nav class="bottom-nav">
  <a href="{{ url('/home') }}" class="nav-item">
    <i class="fa-solid fa-house"></i><span>ホーム</span>
  </a>
  <a href="{{ route('current-goals') }}" class="nav-item active" aria-current="page">
    <i class="fa-solid fa-list-check"></i><span>目標一覧</span>
  </a>
  <a href="{{ url('/pea') }}" class="nav-item">
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
