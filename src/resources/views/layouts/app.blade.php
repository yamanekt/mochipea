{{--
    ログイン後のページ用レイアウト（上部ヘッダー＋ボトムナビあり）

    各ページでの使い方：
      @extends('layouts.app')
      @section('title', 'ページ名')
      @push('css')  @vite(['resources/css/ページ名.css'])  @endpush
      @section('content') ... @endsection
      @push('scripts')  <script src="..."></script>  @endpush
--}}
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | もちぺあ</title>

    {{-- 共通スタイル（トークン・ヘッダー・ナビなど）を先に読み込む --}}
    @vite(['resources/css/common.css'])
    @stack('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @stack('head')
</head>

<body>
    @include('partials.common-header')

    @yield('content')

    @include('partials.common-footer')

    @stack('scripts')
</body>

</html>
