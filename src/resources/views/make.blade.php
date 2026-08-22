@extends('layouts.app')

@section('title', '部屋番号')

@push('css')
    @vite(['resources/css/make.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page made">

        <p class="eyebrow">完了</p>
        <h1 class="page-title">目標をつくりました</h1>
        <p class="page-lead">この番号を相手に伝えるとペアが成立します</p>

        <div class="card code-card">
            <p class="code-label">部屋番号</p>
            <p class="code-value" id="roomCode">{{ $room->room_id }}</p>
            <p class="code-hint">タップでコピー</p>
        </div>

        <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="mascot made-mascot">

        {{-- 番号を渡さないとペアが成立しないので、共有を主操作にする --}}
        <button type="button" class="btn-primary made-share" data-share-code="{{ $room->room_id }}">
            この番号を共有する
        </button>

        <a href="{{ route('pair.waiting') }}" class="btn-text">あとで共有する</a>

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/pair-waiting.js'])
@endpush
