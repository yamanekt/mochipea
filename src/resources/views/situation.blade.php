<<<<<<< HEAD
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/situation.css') }}">
</head>
<body>
<div class="bg"></div>
=======
@extends('layouts.app')

@section('title', '目標の状況')

@push('css')
    @vite(['resources/css/situation.css'])
@endpush

@section('content')
    <div class="page-bg"></div>
    <main class="page situation-page">
        <a href="{{ route('current-goals') }}" class="page-back">‹ <span>目標一覧へ</span></a>
        <p class="eyebrow">目標の状況</p>
        <h1 class="page-title">{{ $goal->title }}</h1>
        <p class="page-lead">ペアと一緒に、少しずつ進めよう</p>
>>>>>>> 7e89c7a (見た目を変更)

        <section class="pair-progress" aria-label="ペアの進捗">
            <article class="member-card member-me">
                <div class="member-heading"><span>あなた</span><strong>{{ $myRate }}%</strong></div>
                <div class="member-track"><span style="width: {{ min(100, $myRate) }}%"></span></div>
                <p class="member-value">{{ $myValue }} <small>/ {{ $goal->target_value }}{{ $goal->unit }}</small></p>
                <img src="{{ asset('images/IMG_0641.png') }}" alt="" class="member-mascot">
                <a href="{{ route('progress.show', $goal->id) }}" class="btn-primary">進捗を記録する</a>
            </article>
            <article class="member-card">
                <div class="member-heading"><span>ペア</span><strong>{{ $partnerRate }}%</strong></div>
                <div class="member-track"><span style="width: {{ min(100, $partnerRate) }}%"></span></div>
                <p class="member-value">{{ $partnerValue }} <small>/ {{ $goal->target_value }}{{ $goal->unit }}</small></p>
            </article>
        </section>

<<<<<<< HEAD
<a href="{{ url('/current-goals') }}" class="back-btn">戻る</a>

<div class="detail-container">

    <div class="goal-title-box">
        {{ $goal->title }}
    </div>

    <div class="pair-area">

        <!-- 自分 -->
        <div class="user-card">

            <div class="speech">
                {{ $goal->current_value }}{{ $goal->unit }}達成したよ
            </div>

            <img src="{{ asset('images/IMG_0640.png') }}" class="character">

            <div class="percent">
                {{ $goal->progress_rate }}%
            </div>
<a href="{{ route('progress.show', $goal->id) }}" class="main-btn">
    記録を更新
</a>

            <div class="history-box">
                更新履歴を見る
            </div>

        </div>

        <!-- 相手 -->
        <div class="user-card">

            <div class="speech">
                {{ $goal->partner_current_value }}{{ $goal->unit }} 達成したよ
            </div>

            <img src="{{ asset('images/IMG_0641.png') }}" class="character">

            <div class="percent">
                {{ $goal->partner_progress_rate }}%
            </div>

            <div class="history-box">
                相手の更新履歴
            </div>

        </div>

    </div>

</div>


</body>
</html>
=======
        <section class="history-section"><h2>あなたの記録</h2>
            @forelse ($myHistory as $progress)
                <article class="history-row"><div><strong>{{ $progress->value }}{{ $goal->unit }}</strong><time>{{ \Carbon\Carbon::parse($progress->progress_date)->format('n月j日') }}</time></div>@if($progress->memo)<p>{{ $progress->memo }}</p>@endif</article>
            @empty <p class="history-empty">まだ記録がありません</p> @endforelse
        </section>
        <section class="history-section"><h2>ペアの記録</h2>
            @forelse ($partnerHistory as $progress)
                <article class="history-row"><div><strong>{{ $progress->value }}{{ $goal->unit }}</strong><time>{{ \Carbon\Carbon::parse($progress->progress_date)->format('n月j日') }}</time></div>@if($progress->memo)<p>{{ $progress->memo }}</p>@endif</article>
            @empty <p class="history-empty">まだ記録がありません</p> @endforelse
        </section>
    </main>
@endsection
>>>>>>> 7e89c7a (見た目を変更)
