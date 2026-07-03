<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>部屋に参加</title>
  <link rel="stylesheet" href="{{ asset('css/make.css') }}">
</head>

<body>
  <div class="bg"></div>
  <div class="container">
    <h1>部屋に参加</h1>
    <h2>相手から受け取った部屋番号を入力してください</h2>

    <form action="{{ route('pair.code.check.post') }}" method="post">
      @csrf
      <label>4桁の部屋番号 *</label>
      <input type="text" name="code" minlength="4" maxlength="4" inputmode="numeric" required>
      @error('code')
        <p class="error">{{ $message }}</p>
      @enderror
      <button type="submit" class="main-btn">参加する</button>
    </form>
  </div>
</body>

</html>
