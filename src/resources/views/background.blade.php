<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="{{ asset('css/background.css') }}">
</head>
<body>
<div class="bg"></div>
<link rel="stylesheet" href="{{ asset('css/background.css') }}">
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
                {{ $goal->current_value }}{{ $goal->unit }}覚えたよ！
            </div>

            <img src="{{ asset('images/IMG_0638.png') }}" class="character">

            <div class="percent">
                {{ $goal->progress_rate }}%
            </div>

            <a href="#" class="main-btn">
                記録を更新
            </a>

            <div class="history-box">
                更新履歴を見る
            </div>

        </div>

        <!-- 相手 -->
        <div class="user-card">

            <div class="speech">
                {{ $goal->partner_current_value }}{{ $goal->unit }}覚えたよ！
            </div>

            <img src="{{ asset('images/IMG_0639.png') }}" class="character">

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
