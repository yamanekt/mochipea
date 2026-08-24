@extends('layouts.app')
@section('title', 'ホーム')
@push('css') @vite(['resources/css/home.css']) @endpush
@section('content')
<div class="page-bg"></div><main class="page home-page"><section class="home-hero"><div><p class="eyebrow">もちぺあへようこそ</p><h1>今日も、<br>いっしょにがんばろう。</h1><p>目標をペアと共有して、楽しく続けよう。</p></div><img src="{{ asset('images/IMG_0639.png') }}" alt=""></section><nav class="home-menu" aria-label="メインメニュー"><a href="{{ route('current-goals') }}" class="home-menu-card"><span class="home-menu-icon">▤</span><span><strong>目標一覧</strong><small>いま取り組んでいる目標を見る</small></span><b>›</b></a><a href="{{ route('goals') }}" class="home-menu-card"><span class="home-menu-icon plus">＋</span><span><strong>目標をつくる</strong><small>新しい目標をペアと始める</small></span><b>›</b></a><a href="{{ route('timeline') }}" class="home-menu-card"><span class="home-menu-icon">♡</span><span><strong>タイムライン</strong><small>みんなのがんばりを見る</small></span><b>›</b></a></nav></main>
@endsection
