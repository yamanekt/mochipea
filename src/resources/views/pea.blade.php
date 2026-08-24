@extends('layouts.app')
@section('title', 'ペア設定')
@push('css') @vite(['resources/css/pea.css']) @endpush
@section('content')
<div class="page-bg"></div><main class="page pea"><p class="eyebrow">ペア設定</p><h1 class="page-title">だれと競う？</h1><p class="page-lead">目標ごとに相手とペアを組みます</p><nav class="pea-menu"><a href="{{ route('goals') }}" class="card menu-card"><span class="menu-text"><span class="menu-title">目標をつくる</span><span class="menu-desc">目標を決めて部屋番号を発行します</span></span><span class="row-chevron">›</span></a><a href="{{ route('join') }}" class="card menu-card"><span class="menu-text"><span class="menu-title">部屋に参加する</span><span class="menu-desc">相手から聞いた番号を入力します</span></span><span class="row-chevron">›</span></a><a href="{{ route('pair.waiting') }}" class="card menu-card"><span class="menu-text"><span class="menu-title">待っている部屋</span><span class="menu-desc">相手の参加を待っている番号</span></span><span class="menu-right">@if($waitingCount > 0)<span class="pill pill-solid">{{ $waitingCount }}</span>@endif<span class="row-chevron">›</span></span></a></nav><img src="{{ asset('images/IMG_0639.png') }}" alt="" class="mascot pea-mascot"></main>
@endsection
