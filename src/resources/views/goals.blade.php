<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>目標登録</title>
  <link rel="stylesheet" href="{{ asset('css/goal.css') }}">
</head>
<body>
     <a href="{{ url('/pea') }}" class="back-btn">戻る</a>
  <div class="bg">

<div class="rolling-area">

    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r1">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r2">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r3">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r4">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r5">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r6">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r7">

</div>

  </div>
  <div class="container">
    <h1>目標登録</h1>
    <h2>目標設定</h2>

<form action="{{ url('/goals/store') }}" method="POST">
      @csrf
<label for="category">目標カテゴリ *</label>

<select name="category" id="category" required>
    <option value="">選択してください</option>
    <option value="exercise">運動</option>
    <option value="study">勉強</option>
    <option value="game">ゲーム</option>
    <option value="other">その他</option>
</select>

      <label>目標名 *</label>
      <input type="text" name="title" required>

      <label>目標値 *</label>

<input
    type="text"
    name="target_value"
    inputmode="numeric"
    pattern="[0-9]*"
    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
    required
>

      <label>単位 *</label>
      <input type="text" name="unit" required>

      <label>期限 *</label>
      <input type="date" name="deadline" required>

     <div class="button-area">
    <button type="submit" class="main-btn">目標決定</button>

    <img src="{{ asset('images/IMG_0641.png') }}"
         alt="キャラ"
         class="point-char">
</div>
    </form>
  </div>
</body>
</html>
