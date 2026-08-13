<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>達成入力</title>

    @vite(['resources/css/common.css', 'resources/css/progress.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
@include('partials.common-header')

<div class="bg"></div>

<div class="container panel">

    <h2>達成入力</h2>

    <div class="goal-info">
        <p><strong>目標名：</strong>{{ $goal->title }}</p>

        <p><strong>現在の達成数：</strong>{{ $current }}{{ $goal->unit }}</p>

        <p><strong>目標値：</strong>{{ $goal->target_value }}{{ $goal->unit }}</p>
    </div>

 <form action="{{ route('progress.store') }}" method="POST">
    @csrf
    <input type="hidden" name="goal_id" value="{{ $goal->id }}">
      <label for="value">達成数</label>
      <input type="number" id="value" name="value" placeholder="達成した数を入力" required>

      <label for="memo">メモ</label>
      <textarea id="memo" name="memo" rows="5" placeholder="今日の内容や感想を入力"></textarea>

      <label for="progress_date">達成日</label>
      <input type="date" id="progress_date" name="progress_date" value="{{ old('progress_date', now()->toDateString()) }}" required>

  <div class="button-area">
    <button type="submit">登録</button>

    <img src="{{ asset('images/IMG_0641.png') }}"
         alt="キャラ"
         class="point-char">
         <a href="{{ route('situation.show', $goal->id) }}" class="back-btn">戻る</a>
          </form>

</div>
<!-- フォームの外 -->
<div class="catch-area">
    <img src="{{ asset('images/IMG_0638.png') }}" class="ghost left-ghost">

    <div class="ball"></div>

    <img src="{{ asset('images/IMG_0639.png') }}" class="ghost right-ghost">
</div>

@include('partials.common-footer')
</body>
</html>
