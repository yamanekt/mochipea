<header class="top-header">
    {{-- ロゴはホームへの導線も兼ねる --}}
    <a href="{{ route('home') }}" class="logo" aria-label="もちぺあ ホーム">もちぺあ</a>

    <div class="header-right">
        <a href="{{ route('notices') }}" class="icon-btn notice" aria-label="お知らせ">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.7 21a2 2 0 0 1-3.4 0"/>
            </svg>
            <small>お知らせ</small>
        </a>
    </div>
</header>
