@extends('layouts.app')

@section('title', 'ペア設定')

@push('css')
    @vite(['resources/css/pea.css'])
@endpush

@section('content')
  <div class="bg"></div>

<div class="rolling-area">
       <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r1">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r2">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r3">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r4">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r5">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r6">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r7">
</div>

<div class="container">

        <a href="{{ url('/goals') }}" class="box">
            <h1>部屋を作る</h1>
            <p>表示された部屋番号を相手に伝えてください</p>
        </a>

        <a href="{{ url('/join') }}" class="box">
            <h1>部屋を探す</h1>
            <p>部屋番号を教えてもらってください</p>
        </a>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pea.js') }}"></script>
@endpush
