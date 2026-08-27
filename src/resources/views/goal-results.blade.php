@extends('layouts.app')

@section('title', '対戦履歴')

@push('css')
    @vite(['resources/css/goals.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page results-page">

        <p class="eyebrow">対戦履歴</p>
        <h1 class="page-title">これまでの結果</h1>
        <p class="page-lead">期限を迎えた目標の勝ち負けを振り返れます</p>

        {{-- 通算成績 --}}
        <div class="card result-summary">
            @foreach (\App\Models\Goal::RESULT_LABELS as $key => $label)
                <div class="result-summary-item">
                    <strong>{{ $summary[$key] }}</strong>
                    <span>{{ $label }}</span>
                </div>
            @endforeach
        </div>

        <div class="goal-list">
            @forelse ($goals as $goal)
                <article class="goal-card">
                    <div class="goal-card-header">
                        <p class="goal-title">{{ $goal->title }}</p>
                        <span class="result-badge result-badge-{{ $goal->my_result }}">
                            {{ \App\Models\Goal::RESULT_LABELS[$goal->my_result] }}
                        </span>
                    </div>

                    <p>ペア相手：{{ $goal->partner_name }}</p>
                    <p>すすめかた：{{ \App\Models\Goal::MODES[$goal->mode] ?? $goal->mode }}</p>
                    <p>あなた {{ $goal->my_value }}{{ $goal->unit }} ／ 相手 {{ $goal->partner_value }}{{ $goal->unit }}</p>

                    @if ($goal->finished_at)
                        <p class="result-date">
                            {{ \Carbon\Carbon::parse($goal->finished_at)->format('Y/m/d') }} に決着
                        </p>
                    @endif

                    <a href="{{ route('situation.show', $goal->id) }}" class="detail-btn">詳細を見る</a>
                </article>
            @empty
                <div class="goal-card">
                    <p>まだ決着した目標がありません。</p>
                </div>
            @endforelse
        </div>

    </main>
@endsection
