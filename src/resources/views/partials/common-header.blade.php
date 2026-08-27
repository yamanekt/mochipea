<header class="top-header">
    {{-- ロゴはホームへの導線も兼ねる --}}
    <a href="{{ route('home') }}" class="logo" aria-label="もちぺあ ホーム">もちぺあ</a>

    <div class="header-right">
        <a href="{{ route('notices') }}" class="icon-btn notice" aria-label="お知らせ">
            <i class="fa-regular fa-bell" aria-hidden="true"></i>
            <small>お知らせ</small>
        </a>
    </div>
</header>
