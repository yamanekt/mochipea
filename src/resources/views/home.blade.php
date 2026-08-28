@extends('layouts.app')

@section('title', 'ホーム')

@push('css')
    @vite(['resources/css/home.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    {{-- オープニング演出（初回だけ出す。home.js が消す） --}}
    <div class="intro">
        <img src="{{ asset('images/IMG_0639.png') }}" alt="">
    </div>

    <main class="page home-page">

        <section class="home-hero">
            <div>
                <p class="eyebrow">もちぺあ</p>
                <h1>今日も、<br>いっしょに。</h1>
                <p class="home-hero-lead">ペアと目標を共有して、続けよう</p>
            </div>
            <img src="{{ asset('images/IMG_0639.png') }}" alt="">
        </section>

        <nav class="home-menu" aria-label="メインメニュー">
            @foreach ([
                ['route' => 'current-goals', 'label' => '目標一覧',   'desc' => 'いま取り組んでいる目標を見る'],
                ['route' => 'goals',         'label' => '目標をつくる', 'desc' => '新しい目標をペアと始める'],
                ['route' => 'timeline',      'label' => 'タイムライン', 'desc' => 'みんなのがんばりを見る'],
                ['route' => 'goal.results',  'label' => '対戦履歴',    'desc' => 'これまでの勝ち負けを振り返る'],
            ] as $item)
                <a href="{{ route($item['route']) }}" class="card home-menu-card">
                    <span class="home-menu-text">
                        <strong>{{ $item['label'] }}</strong>
                        <small>{{ $item['desc'] }}</small>
                    </span>
                    <span class="row-chevron" aria-hidden="true">›</span>
                </a>
            @endforeach
        </nav>

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/home.js'])
@endpush
