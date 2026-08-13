<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>目標登録</title>
  @vite(['resources/css/common.css','resources/css/goal.css'])
  <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <header class="top-header">
        <h1 class="logo">もちぺあ</h1>

        <div class="header-right">
            <a href="#" class="icon-btn notice">
                <i class="fa-regular fa-bell"></i>
                <small>お知らせ</small>
            </a>
        </div>
    </header>
    @include('partials.common-footer')
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
  <div class="container panel">
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
      <input type="text" name="title" placeholder="例：英単語を覚える" required>

      <label>目標値 *</label>

<input
    type="text"
    name="target_value"
    inputmode="numeric"
    pattern="[0-9]*"
    placeholder="例：100"
    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
    required
>

      <label>単位 *</label>
      <input type="text" name="unit" placeholder="例：回・語・分" required>

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
