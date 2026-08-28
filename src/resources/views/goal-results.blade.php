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
                <article class="card goal-card">
                    <div class="goal-card-header">
                        <p class="goal-title">{{ $goal->title }}</p>
                        <span class="result-badge result-badge-{{ $goal->my_result }}">
                            {{ \App\Models\Goal::RESULT_LABELS[$goal->my_result] }}
                        </span>
                    </div>

                    <p class="goal-partner">
                        ペア相手：{{ $goal->partner_name }}／{{ \App\Models\Goal::MODES[$goal->mode] ?? $goal->mode }}
                    </p>

                    <p class="result-score">
                        あなた {{ $goal->my_value }}{{ $goal->unit }}
                        <span>／</span>
                        相手 {{ $goal->partner_value }}{{ $goal->unit }}
                    </p>

                    <div class="goal-card-footer">
                        <span>
                            @if ($goal->finished_at)
                                {{ \Carbon\Carbon::parse($goal->finished_at)->format('n月j日') }} に決着
                            @endif
                        </span>
                        <a href="{{ route('situation.show', $goal->id) }}">詳細を見る ›</a>
                    </div>
                </article>
            @empty
                <div class="card empty-card">
                    <p>まだ決着した目標がありません。</p>
                </div>
            @endforelse
        </div>

    </main>
@endsection
