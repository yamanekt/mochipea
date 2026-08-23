<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>進行中の目標</title>
  <link rel="stylesheet" href="{{ asset('css/goals.css') }}">
</head>
<header class="page-menu">

<<<<<<< HEAD
    <a href="{{ url('/timeline') }}" class="menu-btn">
        タイムライン
    </a>
=======
@section('title', '目標一覧')
>>>>>>> 7e89c7a (見た目を変更)

    <div class="menu-btn current">
        目標一覧
    </div>

</header>
<header>

<<<<<<< HEAD
    <form method="GET" action="{{ route('current-goals') }}" class="sort-form">
=======
@section('content')
    <div class="page-bg"></div>
    <main class="page goals-page">
        <p class="eyebrow">目標一覧</p>
        <h1 class="page-title">いま頑張っている目標</h1>
        <p class="page-lead">ペアと進み具合を見比べながら続けよう</p>
>>>>>>> 7e89c7a (見た目を変更)

        <form method="GET" action="{{ route('current-goals') }}" class="sort-form" aria-label="並び順">
            <label class="sort-option"><input type="radio" name="sort" value="updated" {{ $sort === 'updated' ? 'checked' : '' }} onchange="this.form.submit()">更新順</label>
            <label class="sort-option"><input type="radio" name="sort" value="created" {{ $sort === 'created' ? 'checked' : '' }} onchange="this.form.submit()">作成順</label>
        </form>

<<<<<<< HEAD
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
<body>

 <div class="bg"></div>
<div class="guide-character">
    <img src="{{ asset('images/IMG_0639.png') }}" alt="案内キャラクター">
</div>

<a href="{{ url('/home') }}" class="back-btn">戻る</a>



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
</body>
</html>
=======
        <div class="goal-list">
            @forelse ($goals as $goal)
                @php $progressRate = min(100, max(0, $goal->progress_rate)); @endphp
                <article class="goal-card">
                    <div class="goal-card-header">
                        <div>
                            <p class="goal-category">{{ \App\Http\Controllers\GoalFlowController::CATEGORIES[$goal->category] ?? $goal->category }}</p>
                            <h2 class="goal-title">{{ $goal->title }}</h2>
                        </div>
                        <span class="status-badge {{ $goal->display_status === '期限切れ' ? 'status-expired' : 'status-active' }}">{{ $goal->display_status }}</span>
                    </div>
                    <p class="goal-partner">ペア：{{ $goal->partner_name }}</p>
                    <div class="progress-block">
                        <div class="progress-label"><span>{{ $goal->current_value }} / {{ $goal->target_value }}{{ $goal->unit }}</span><strong>{{ $goal->progress_rate }}%</strong></div>
                        <div class="progress-track"><span class="progress-fill" style="width: {{ $progressRate }}%"></span></div>
                    </div>
                    <div class="goal-card-footer"><span>期限 {{ \Carbon\Carbon::parse($goal->deadline)->format('n月j日') }}</span><a href="{{ route('situation.show', $goal->id) }}">詳細を見る <span aria-hidden="true">›</span></a></div>
                </article>
            @empty
                <div class="empty-card"><img src="{{ asset('images/IMG_0639.png') }}" alt=""><p>まだ目標がありません</p><a href="{{ route('goals') }}" class="btn-primary">目標をつくる</a></div>
            @endforelse
        </div>
    </main>
@endsection
>>>>>>> 7e89c7a (見た目を変更)
