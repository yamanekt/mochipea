@extends('layouts.app')

@section('title', '目標の現在状況')

@push('css')
    @vite(['resources/css/situation.css'])
@endpush

@php
    $lead = $myValue - $partnerValue;
@endphp

@section('content')
    <div class="page-bg"></div>

    <main class="page situation-page">

        <a href="{{ route('current-goals') }}" class="page-back">← 目標一覧に戻る</a>

        <p class="eyebrow">目標の現在状況</p>
        <h1 class="page-title">{{ $goal->title }}</h1>
        <p class="page-lead">
            期限 {{ \Carbon\Carbon::parse($goal->deadline)->format('Y / m / d') }}
            ・{{ \App\Models\Goal::MODES[$goal->mode] ?? $goal->mode }}
        </p>

        {{-- 決着したら結果を最初に出す --}}
        @if ($result)
            <p class="goal-result goal-result-{{ $result }}">
                {{ \App\Models\Goal::RESULT_LABELS[$result] }}
            </p>
        @elseif ($lead !== 0)
            {{-- 差を先に出して、見比べる手間をなくす --}}
            <p class="lead-note">
                @if ($lead > 0)
                    あなたが {{ $lead }}{{ $goal->unit }} リードしています！
                @else
                    相手が {{ abs($lead) }}{{ $goal->unit }} リードしています
                @endif
            </p>
        @endif

        {{-- Figma準拠：自分と相手を横に並べて見比べる --}}
        <div class="pair-area">
            @foreach ([
                ['me' => true,  'name' => 'あなた',   'value' => $myValue,      'rate' => $myRate,      'over' => $myOverflow,      'hp' => $partnerRemainingHp, 'img' => 'IMG_0640.png'],
                ['me' => false, 'name' => 'ペア相手', 'value' => $partnerValue, 'rate' => $partnerRate, 'over' => $partnerOverflow, 'hp' => $myRemainingHp,      'img' => 'IMG_0641.png'],
            ] as $side)
                <section class="member{{ $side['me'] ? ' member-me' : '' }}">

                    <p class="member-name">
                        <span class="member-avatar">
                            <img src="{{ asset('images/' . $side['img']) }}" alt="">
                        </span>
                        {{ $side['name'] }}
                    </p>

                    <div class="card member-card">
                        <p class="member-speech">
                            {{ $side['value'] }} / {{ $goal->target_value }} {{ $goal->unit }}
                        </p>

                        @if ($goal->isSurvival())
                            {{-- サバイバルは相手のHPをどれだけ削ったかを見せる --}}
                            <p class="member-hp">
                                <strong>{{ $side['hp'] }}</strong>
                                <span>相手の残りHP</span>
                            </p>
                        @else
                            {{-- 円形の達成率リング。conic-gradient で塗り分ける --}}
                            <div class="ring" style="--rate: {{ min(100, $side['rate']) }}">
                                <div class="ring-inner">
                                    <strong>{{ $side['rate'] }}%</strong>
                                    <span>達成率</span>
                                </div>
                            </div>
                        @endif

                        @if ($side['over'] > 0)
                            <p class="member-overflow">
                                目標達成！さらに {{ $side['over'] }}{{ $goal->unit }} 上乗せ中
                            </p>
                        @endif
                    </div>

                </section>
            @endforeach
        </div>

        @if (! $result)
            <a href="{{ route('progress.show', $goal->id) }}" class="btn-primary">記録を更新する</a>
        @endif

        {{-- 更新履歴 --}}
        @foreach ([['あなたの記録', $myHistory], ['ペアの記録', $partnerHistory]] as [$label, $history])
            <section class="card history-card">
                <span class="card-label">{{ $label }}</span>

                @forelse ($history as $progress)
                    <div class="history-row">
                        <div class="history-head">
                            <strong>{{ $progress->value }}{{ $goal->unit }}</strong>
                            <time>{{ \Carbon\Carbon::parse($progress->progress_date)->format('n月j日') }}</time>
                        </div>
                        @if ($progress->memo)
                            <p>{{ $progress->memo }}</p>
                        @endif
                    </div>
                @empty
                    <p class="history-empty">まだ記録がありません</p>
                @endforelse
            </section>
        @endforeach

    </main>
@endsection
