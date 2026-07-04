<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>目標の現在状況</title>
    <link rel="stylesheet" href="{{ asset('css/situation.css') }}">
</head>

<body>

    <div class="bg"></div>

    <div class="container">

        <div class="goal-card">

            <p><strong>目標名：</strong>{{ $goal->title }}</p>

            <p>
                <strong>カテゴリ：</strong>

                @if ($goal->category == 'exercise')
                    運動
                @elseif($goal->category == 'study')
                    勉強
                @elseif($goal->category == 'game')
                    ゲーム
                @else
                    {{ $goal->category }}
                @endif
            </p>

            <p>
                <strong>目標値：</strong>
                {{ $goal->target_value }}{{ $goal->unit }}
            </p>

            <hr>

            <h3>自分</h3>

            <p>
                達成数：
                {{ $myValue }}{{ $goal->unit }}
            </p>

            <p>
                達成率：
                {{ $myRate }}%
            </p>

            <hr>

            <h3>ペア相手</h3>



            <p>
                達成数：
                {{ $partnerValue }}{{ $goal->unit }}
            </p>

            <p>
                達成率：
                {{ $partnerRate }}%
            </p>

            <hr>

            <p>
                <strong>期限：</strong>
                {{ \Carbon\Carbon::parse($goal->deadline)->format('Y/m/d') }}
            </p>

            <p>
                <strong>状態：</strong>

                @if ($goal->status)
                    {{ $goal->status }}
                @else
                    進行中
                @endif
            </p>

            <div class="button-area">

                <a href="{{ route('progress.show', $goal->id) }}" class="btn">
                    達成入力
                </a>

                <a href="{{ route('current-goals') }}" class="btn">
                    一覧へ戻る
                </a>

            </div>

            <div class="catch-area">
                <img src="{{ asset('images/IMG_0638.png') }}" class="ghost left-ghost" alt="左キャラ">

                <div class="ball"></div>

                <img src="{{ asset('images/IMG_0639.png') }}" class="ghost right-ghost" alt="右キャラ">
            </div>



        </div>

    </div>
</body>

</html>
