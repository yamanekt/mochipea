<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>進行中の目標</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}?v={{ filemtime(public_path('css/all.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/current-goals.css') }}?v={{ filemtime(public_path('css/current-goals.css')) }}">
</head>
<header class="page-menu">

    <a href="{{ url('/timeline') }}" class="menu-btn">
        タイムライン
    </a>

    <div class="menu-btn current">
        目標一覧
    </div>

</header>
<header>

    <form method="GET" action="{{ route('current-goals') }}" class="sort-form">

        <label class="sort-btn">
            <input type="radio" name="sort" value="updated" {{ $sort == 'updated' ? 'checked' : '' }}
                onchange="this.form.submit()">
            <span>更新順</span>
        </label>

        <label class="sort-btn">
            <input type="radio" name="sort" value="created" {{ $sort == 'created' ? 'checked' : '' }}
                onchange="this.form.submit()">
            <span>登録順</span>
        </label>

    </form>

    <body>

        <div class="bg"></div>
        <div class="guide-character">
            <img src="{{ asset('images/IMG_0639.png') }}" alt="案内キャラクター">
        </div>

        <a href="{{ url('/home') }}" class="back-btn">戻る</a>



        <div class="container">

            @forelse ($goals as $goal)
                @php
                    $progressRate = min(100, max(0, $goal->progress_rate));
                    $isExpired = $goal->display_status === '期限切れ';
                @endphp

                <div class="box">
                    <div class="goal-card-header">
                        <p class="box-title">目標名：{{ $goal->title }}</p>
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
                <div class="box">
                    <p>現在登録されている目標はありません。</p>
                </div>
            @endforelse
        </div>
    </body>

</html>
