<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ホーム</title>
  @vite(['resources/css/common.css','resources/css/home.css'])
  <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    @include('partials.common-header')

    {{-- オープニング演出 --}}
    <div class="intro">
        <img src="{{ asset('images/IMG_0639.png') }}" alt="">
    </div>

    <div class="bg"></div>

    @include('partials.common-footer')

    <script src="{{ asset('js/home.js') }}?v={{ filemtime(public_path('js/home.js')) }}"></script>
</body>

</html>
