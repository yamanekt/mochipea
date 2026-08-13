@extends('layouts.app')

@section('title', 'ホーム')

@push('css')
    @vite(['resources/css/home.css'])
@endpush

@section('content')
    {{-- オープニング演出 --}}
    <div class="intro">
        <img src="{{ asset('images/IMG_0639.png') }}" alt="">
    </div>

    <div class="bg"></div>
@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}?v={{ filemtime(public_path('js/home.js')) }}"></script>
@endpush
