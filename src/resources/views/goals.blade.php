<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>目標登録</title>
  <link rel="stylesheet" href="{{ asset('css/goal.css') }}">
</head>
<body>
  <div class="bg"></div>
  <div class="container">
    <h1>目標登録</h1>
    <h2>目標設定</h2>

    <form action="{{ url('/make') }}" method="get">
      @csrf
      <label>目標カテゴリ *</label>
      <input type="text" name="category" required>

      <label>目標名 *</label>
      <input type="text" name="title" required>

      <label>目標値 *</label>
      <input type="text" name="target_value" required>

      <label>単位 *</label>
      <input type="text" name="unit" required>

      <label>期限 *</label>
      <input type="date" name="deadline" required>

      <div class="button">
        <button type="submit" class="main-btn">目標決定</button>
      </div>
    </form>
  </div>
</body>
</html>
