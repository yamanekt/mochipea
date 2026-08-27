{{--
    PWA（ホーム画面に追加・通知）のためのタグ。
    全レイアウトの <head> で読み込む。

    asset() を通しているのは、サブディレクトリ配置（/~sys1_26_hijyo/ など）でも
    正しいURLに解決させるため。manifest 側の start_url / scope も相対パスにしてある。
--}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#46d3a5">

{{-- iOS はまだ manifest だけでは足りないので個別に指定する --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="もちぺあ">
<link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192.png') }}">

@auth
    {{-- 購読の保存はPOSTなのでCSRFトークンが要る --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endauth

<script>
    // Service Worker の位置を JS へ渡す（サブディレクトリ対応）
    window.MOCHIPEA_SW_URL = "{{ asset('sw.js') }}";

    // VAPID公開鍵。未設定なら空文字になり、購読処理は行われない
    window.MOCHIPEA_VAPID_KEY = @json(config('webpush.public_key') ?? '');
    @auth
        window.MOCHIPEA_SUBSCRIBE_URL = "{{ route('push.subscribe') }}";
    @endauth
</script>
