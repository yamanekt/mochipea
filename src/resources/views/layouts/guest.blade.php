{{--
    ログイン前のページ用レイアウト（ヘッダー・ボトムナビなし）
    ログイン／新規登録で使う

    各ページでの使い方：
      @extends('layouts.guest')
      @section('title', 'ページ名')
      @push('css')  @vite(['resources/css/ページ名.css'])  @endpush
      @section('content') ... @endsection
--}}
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | もちぺあ</title>

    {{-- 共通スタイル（トークンなど）を先に読み込む --}}
    @vite(['resources/css/common.css'])
    @stack('css')

    @stack('head')
</head>

<body>
    @yield('content')

    @stack('scripts')
</body>

</html>
