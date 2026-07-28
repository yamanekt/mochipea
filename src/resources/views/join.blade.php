<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>部屋に参加</title>
  <link rel="stylesheet" href="{{ asset('css/join.css') }}">
</head>

<body>
  <div class="bg"></div>
  <a href="{{ url('/pea') }}" class="back-btn">戻る</a>
  <div id="pop-area"></div>
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
  <script>
    const POP_IMAGES = [
      "{{ asset('images/IMG_0639.png') }}",
      "{{ asset('images/IMG_0638.png') }}"
    ];
  </script>
  <script src="{{ asset('js/join.js') }}"></script>
</body>

</html>
