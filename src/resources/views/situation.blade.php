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


<a href="{{ url('/current-goals') }}" class="back-btn">戻る</a>

<div class="detail-container">

    <div class="goal-title-box">
        {{ $goal->title }}
    </div>

    <div class="pair-area">

        <!-- 自分 -->
        <div class="user-card">

            <div class="speech">
                {{ $myValue }}{{ $goal->unit }}達成したよ！
            </div>

            <img src="{{ asset('images/IMG_0640.png') }}" class="character">

            <div class="percent">
                {{ $myRate }}%
            </div>

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
                {{ $partnerRate }}%
            </div>

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


</body>
</html>
