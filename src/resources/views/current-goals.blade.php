<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>進行中の目標</title>
  <link rel="stylesheet" href="{{ asset('css/goals.css') }}">
</head>

<body>
 <div class="bg"></div>


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
      </div>
    @empty
      <div class="goal-card">
        <p>現在登録されている目標はありません。</p>
      </div>
    @endforelse
  </div>
</body>
</html>
