<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>達成入力</title>
  <link rel="stylesheet" href="{{ asset('css/progress.css') }}">
</head>
<body>
  <div class="bg"></div>
  <div class="container">

    <h2>達成入力</h2>

    <div class="goal-info">
      <p><strong>目標名：</strong>毎日10000歩歩く</p>
      <p><strong>現在の達成数：</strong>5000歩</p>
      <p><strong>目標値：</strong>10000歩</p>
    </div>
<form action="{{ url('/progress') }}" method="get">
    @csrf

    <label for="value">達成数</label>
    <input type="number" id="value" name="value" placeholder="達成した数を入力" required>

    <label for="memo">メモ</label>
    <textarea id="memo" name="memo" rows="5" placeholder="今日の内容や感想を入力"></textarea>

    <label for="progress_date">達成日</label>
    <input type="date" id="progress_date" name="progress_date" required>

    <div class="button-area">
        <button type="submit">登録</button>

        <img src="{{ asset('images/IMG_0641.png') }}"
             class="point-char">
    </div>

    <a href="{{ url('/situation') }}" class="back-btn">戻る</a>

</form>

</div>
<!-- フォームの外 -->
<div class="catch-area">
    <img src="{{ asset('images/IMG_0638.png') }}" class="ghost left-ghost">

    <div class="ball"></div>

    <img src="{{ asset('images/IMG_0639.png') }}" class="ghost right-ghost">
</div>

</body>
</html>
