@extends('layouts.app')

@section('title', '待っている部屋')

@push('css')
    @vite(['resources/css/pair-waiting.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page waiting">

        <p class="eyebrow">ペア設定</p>
        <h1 class="page-title">待っている部屋</h1>
        <p class="page-lead">相手が番号を入力するとペアが成立します</p>

        @forelse ($rooms as $room)
            <div class="card room-card">
                <div class="room-head">
                    <p class="room-code">{{ $room->code }}</p>
                    <span class="pill">待機中</span>
                </div>

                <p class="room-goal">{{ $room->title }}</p>
                <p class="room-date">
                    {{ \Carbon\Carbon::parse($room->created_at)->format('n月j日') }}に作成
                </p>

                <div class="room-actions">
                    {{-- 共有が主操作。番号を渡さないとペアが成立しないため --}}
                    <button type="button" class="room-share" data-share-code="{{ $room->code }}">
                        番号を共有
                    </button>
                    <span class="room-cancel">取り消す</span>
                </div>
            </div>
        @empty
            <div class="card room-empty">
                <p>待っている部屋はありません。</p>
                <a href="{{ route('goals') }}" class="btn-primary">目標をつくる</a>
            </div>
        @endforelse

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/pair-waiting.js'])
@endpush
