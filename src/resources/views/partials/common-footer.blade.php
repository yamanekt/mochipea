@php
    // 選択判定はルート名で行う。アイコンは通常／選択中の2枚を差し替える
    $navItems = [
        [
            'route'  => 'home',
            'label'  => 'ホーム',
            'icon'   => 'home',
            'active' => request()->routeIs('home') || request()->is('home'),
        ],
        [
            'route'  => 'current-goals',
            'label'  => '目標一覧',
            'icon'   => 'goals',
            'active' => request()->routeIs('current-goals', 'situation.show', 'progress.show'),
        ],
        [
            'route'  => 'pea',
            'label'  => '目標作成',
            'icon'   => 'create',
            'active' => request()->routeIs('pea', 'pair.waiting', 'goals', 'make', 'join'),
        ],
        [
            'route'  => 'timeline',
            'label'  => 'タイムライン',
            'icon'   => 'timeline',
            'active' => request()->routeIs('timeline'),
        ],
        [
            'route'  => 'mypage',
            'label'  => 'マイページ',
            'icon'   => 'mypage',
            'active' => request()->routeIs('mypage'),
        ],
    ];
@endphp

<nav class="bottom-nav" aria-label="メインメニュー">
    @foreach ($navItems as $item)
        <a href="{{ route($item['route']) }}"
           class="nav-item{{ $item['active'] ? ' active' : '' }}"
           @if ($item['active']) aria-current="page" @endif>
            {{-- サブディレクトリ配置でも解決できるよう asset() を通す --}}
            <img src="{{ asset('images/nav/' . $item['icon'] . ($item['active'] ? '-active' : '') . '.svg') }}"
                 alt="" aria-hidden="true">
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
