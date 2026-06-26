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

  <div class="goal-list">

    @forelse ($goals as $goal)

      <div class="goal-card">
        <p>目標名：{{ $goal->title }}</p>

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

        <p>進捗率：{{ $goal->progress_rate }}%</p>

        <p>
          期限：
          {{ \Carbon\Carbon::parse($goal->deadline)->format('Y/m/d') }}
        </p>

        <p>状態：{{ $goal->display_status }}</p>
      </div>

    @empty

      <div class="goal-card">
        <p>現在登録されている目標はありません。</p>
      </div>

    @endforelse

  </div>
</body>
</html>
