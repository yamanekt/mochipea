<nav class="bottom-nav" aria-label="メインメニュー">
    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') || request()->is('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house" aria-hidden="true"></i>
        <span>ホーム</span>
    </a>

    <a href="{{ route('current-goals') }}" class="nav-item {{ request()->routeIs('current-goals', 'goals.show', 'background', 'situation.show', 'progress.show') ? 'active' : '' }}">
        <i class="fa-solid fa-list-check" aria-hidden="true"></i>
        <span>目標一覧</span>
    </a>

    <a href="{{ route('pea') }}" class="nav-item {{ request()->routeIs('pea', 'goals', 'goals.store', 'make', 'join') ? 'active' : '' }}">
        <i class="fa-solid fa-circle-plus" aria-hidden="true"></i>
        <span>目標作成</span>
    </a>

    <a href="{{ route('timeline') }}" class="nav-item {{ request()->routeIs('timeline') ? 'active' : '' }}">
        <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
        <span>タイムライン</span>
    </a>

    <a href="{{ route('mypage') }}" class="nav-item {{ request()->routeIs('mypage') ? 'active' : '' }}">
        <i class="fa-regular fa-user" aria-hidden="true"></i>
        <span>マイページ</span>
    </a>
</nav>
