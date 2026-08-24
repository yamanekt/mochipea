@extends('layouts.app')

@section('title', '部屋を作成しました')

@push('css')
    @vite(['resources/css/make.css'])
@endpush

@section('content')
    <div class="page-bg"></div>
    <main class="page made">
        <p class="eyebrow">目標作成</p>
        <h1 class="page-title">目標をつくりました</h1>
        <p class="page-lead">この番号を相手に伝えると、ペアが完成します</p>

        @if (isset($room) && filled($room->room_id ?? null))
            <div class="card code-card">
                <p class="code-label">部屋番号</p>
                <p class="code-value" id="roomCode">{{ $room->room_id }}</p>
                <p class="code-hint">タップしてコピー</p>
            </div>
            <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="mascot made-mascot">
            <button type="button" class="btn-primary made-share" data-share-code="{{ $room->room_id }}">この番号を共有する</button>
        @else
            <div class="card code-card"><p class="code-label">部屋番号</p><p class="code-hint">目標を作成すると、ここに部屋番号が表示されます。</p></div>
            <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="mascot made-mascot">
            <a href="{{ route('goals') }}" class="btn-primary">目標をつくる</a>
        @endif

        <a href="{{ route('pea') }}" class="btn-text">ペア設定へ戻る</a>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelector('[data-share-code]')?.addEventListener('click', async (event) => {
            const code = event.currentTarget.dataset.shareCode;
            await navigator.clipboard?.writeText(code);
            event.currentTarget.textContent = 'コピーしました';
        });
    </script>
@endpush

