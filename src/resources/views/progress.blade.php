<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>達成入力</title>

<<<<<<< HEAD
    <link rel="stylesheet" href="{{ asset('css/progress.css') }}">
</head>
=======
@section('title', '進捗を記録')
>>>>>>> 7e89c7a (見た目を変更)

<body>

<<<<<<< HEAD
<div class="bg"></div>

<div class="container">

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

</body>
</html>
=======
@section('content')
    <div class="page-bg"></div>
    <main class="page progress-page">
        <a href="{{ route('situation.show', $goal->id) }}" class="page-back">‹ <span>目標の状況へ</span></a>
        <p class="eyebrow">進捗を記録</p>
        <h1 class="page-title">今日のがんばりを残そう</h1>
        <div class="progress-goal-card"><p>{{ $goal->title }}</p><strong>{{ $current }} <small>/ {{ $goal->target_value }}{{ $goal->unit }}</small></strong></div>
        <form action="{{ route('progress.store') }}" method="POST" class="progress-form">
            @csrf
            <input type="hidden" name="goal_id" value="{{ $goal->id }}">
            <label>今回の進捗 <span>{{ $goal->unit }}</span><input type="number" name="value" min="1" inputmode="numeric" placeholder="例：10" required></label>
            <label>ひとこと <span>任意</span><textarea name="memo" rows="4" placeholder="今日がんばったことを書いてみよう"></textarea></label>
            <label>記録した日<input type="date" name="progress_date" value="{{ old('progress_date', now()->toDateString()) }}" required></label>
            <img src="{{ asset('images/IMG_0641.png') }}" alt="" class="progress-mascot">
            <button type="submit" class="btn-primary">記録する</button>
        </form>
    </main>
@endsection
>>>>>>> 7e89c7a (見た目を変更)
