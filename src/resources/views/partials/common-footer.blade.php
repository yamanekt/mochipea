@php
    $navItems = [
        ['route' => 'home', 'label' => 'ホーム', 'icon' => 'fa-house', 'active' => request()->routeIs('home') || request()->is('home')],
        ['route' => 'current-goals', 'label' => '目標一覧', 'icon' => 'fa-clipboard-list', 'active' => request()->routeIs('current-goals', 'goals.show', 'situation.show', 'progress.show')],
        ['route' => 'pea', 'label' => '目標作成', 'icon' => 'fa-plus', 'active' => request()->routeIs('pea', 'pair.waiting', 'goals', 'goals.new.*', 'make', 'join')],
        ['route' => 'timeline', 'label' => 'タイムライン', 'icon' => 'fa-arrows-rotate', 'active' => request()->routeIs('timeline')],
        ['route' => 'mypage', 'label' => 'マイページ', 'icon' => 'fa-user', 'active' => request()->routeIs('mypage')],
    ];
@endphp
<nav class="bottom-nav" aria-label="メインメニュー">
    @foreach ($navItems as $item)
        <a href="{{ route($item['route']) }}" class="nav-item{{ $item['active'] ? ' active' : '' }}" @if($item['active']) aria-current="page" @endif><i class="fa-solid {{ $item['icon'] }}" aria-hidden="true"></i><span>{{ $item['label'] }}</span></a>
    @endforeach
</nav>
