<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>部屋に参加</title>
  @vite(['resources/css/common.css', 'resources/css/join.css'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
  @include('partials.common-header')
  <div class="bg"></div>
  <div id="pop-area"></div>
  <div class="container panel">
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
  @include('partials.common-footer')
</body>

</html>
