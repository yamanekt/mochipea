{{--
    PWA（ホーム画面に追加・オフライン表示）のためのタグ。
    全レイアウトの <head> で読み込む。

    asset() を通しているのは、サブディレクトリ配置（/~sys1_26_hijyo/ など）でも
    正しいURLに解決させるため。manifest 側の start_url / scope も相対パスにしてある。
--}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#46d3a5">

{{-- タブのファビコン。指定がないとブラウザはドメイン直下の /favicon.ico を
     探しにいくので、サブディレクトリ配置では外れる。ここも asset() で明示する --}}
<link rel="icon" sizes="32x32" href="{{ asset('favicon.ico') }}">

{{-- 標準の指定。Chrome はこちらを見る --}}
<meta name="mobile-web-app-capable" content="yes">

{{-- iOS Safari はまだ標準名に対応しておらず apple- 付きしか見ないので両方書く。
     Chrome では apple- 付きが非推奨の警告になるが、消すと iOS で
     ホーム画面から開いたときに全画面にならなくなるため残す --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="もちぺあ">
<link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192.png') }}">

<script>
    // Service Worker の位置を JS へ渡す（サブディレクトリ対応）
    window.MOCHIPEA_SW_URL = "{{ asset('sw.js') }}";
</script>
