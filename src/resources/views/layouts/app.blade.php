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

    @include('partials.pwa-head')
    @stack('head')
</head>

<body>
    @include('partials.common-header')

    @yield('content')

    @include('partials.common-footer')

    @vite(['resources/js/pwa.js'])
    @stack('scripts')
</body>

</html>
