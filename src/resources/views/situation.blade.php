@extends('layouts.app')

@section('title', '目標の現在状況')

@push('css')
    @vite(['resources/css/situation.css'])
@endpush

@section('content')
    <div class="bg"></div>


    <a href="{{ url('/current-goals') }}" class="back-btn">戻る</a>

    <div class="detail-container">

        <div class="goal-title-box">
            {{ $goal->title }}
        </div>

        {{-- 期限が来て勝敗が確定したら結果を出す --}}
        @if ($result)
            <div class="goal-result goal-result-{{ $result }}">
                {{ \App\Models\Goal::RESULT_LABELS[$result] }}
            </div>
        @endif

        <div class="pair-area">

            <!-- 自分 -->
            <div class="user-card">

                <div class="speech">
                    {{ $myValue }}{{ $goal->unit }}達成したよ！
                </div>

                <img src="{{ asset('images/IMG_0640.png') }}" class="character">

                <div class="percent">
                    @if ($goal->isSurvival())
                        相手のHP {{ $partnerRemainingHp }}
                    @else
                        {{ $myRate }}%
                    @endif
                </div>

                {{-- 100%到達後も積み上げが見えるようにして、続ける理由を残す --}}
                @if ($myOverflow > 0)
                    <p class="overflow-note">目標達成！さらに {{ $myOverflow }}{{ $goal->unit }} 上乗せ中</p>
                @endif

                <a href="{{ route('progress.show', $goal->id) }}" class="main-btn">
                    記録を更新
                </a>

                <div class="history-box">
                    <div class="history-title">更新履歴</div>
                    @forelse ($myHistory as $progress)
                        <div class="history-item">
                            <div><strong>{{ $progress->value }}{{ $goal->unit }}</strong> 達成したよ！</div>
                            <time>{{ \Carbon\Carbon::parse($progress->progress_date)->format('Y/m/d') }}</time>
                            @if ($progress->memo)
                                <p>{{ $progress->memo }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="history-empty">まだ記録がありません</p>
                    @endforelse
                </div>

            </div>

            <!-- 相手 -->
            <div class="user-card">

                <div class="speech">
                    {{ $partnerValue }}{{ $goal->unit }}達成したよ！
                </div>

                <img src="{{ asset('images/IMG_0641.png') }}" class="character">

                <div class="percent">
                    @if ($goal->isSurvival())
                        あなたのHP {{ $myRemainingHp }}
                    @else
                        {{ $partnerRate }}%
                    @endif
                </div>

                @if ($partnerOverflow > 0)
                    <p class="overflow-note">目標達成！さらに {{ $partnerOverflow }}{{ $goal->unit }} 上乗せ中</p>
                @endif

                <div class="history-box">
                    <div class="history-title">相手の更新履歴</div>
                    @forelse ($partnerHistory as $progress)
                        <div class="history-item">
                            <div><strong>{{ $progress->value }}{{ $goal->unit }}</strong> 達成したよ！</div>
                            <time>{{ \Carbon\Carbon::parse($progress->progress_date)->format('Y/m/d') }}</time>
                            @if ($progress->memo)
                                <p>{{ $progress->memo }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="history-empty">まだ記録がありません</p>
                    @endforelse
                </div>

            </div>

        </div>

    </div>
@endsection
