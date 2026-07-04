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
      <p><strong>目標名：</strong>毎日10000歩歩く</p>
      <p><strong>カテゴリ：</strong>運動</p>
      <p><strong>目標値：</strong>10000歩</p>

      <hr>

      <h3>自分</h3>
      <p>達成数：5000歩</p>
      <p>達成率：50%</p>

      <h3>ペア相手</h3>
      <p>達成数：7000歩</p>
      <p>達成率：70%</p>

      <hr>

      <p><strong>期限：</strong>2026/06/30</p>
      <p><strong>状態：</strong>進行中</p>

      <div class="button-area">
        <a href="{{ url('/progress') }}" class="btn">達成入力</a>
        <a href="{{ url('/current-goals') }}" class="btn">一覧へ戻る</a>
      </div>
    </div>

  </div>
</body>
</html>
