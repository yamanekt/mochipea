@extends('layouts.app')

@section('title', '部屋番号')

@push('css')
    @vite(['resources/css/make.css'])
@endpush

@section('content')
    <div class="bg"></div>
    <div class="container panel">
        <h1>部屋を作成しました</h1>
        <h2>別のアカウントでログインした相手に、この部屋番号を伝えてください</h2>

        <label>部屋番号</label>
        <div class="password-area">
            <div id="roomCode" class="code-box">{{ $room->room_id }}</div>
            <button type="button" class="share-btn">共有</button>
        </div>

        <a href="{{ route('pea') }}" class="main-btn">戻る</a>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/make.js'])
@endpush
