@extends('layouts.app')

@section('title', '部屋に参加')

@push('css')
    @vite(['resources/css/join.css'])
@endpush

@section('content')
    <div class="page-bg"></div>
    <div id="pop-area" aria-hidden="true"></div>

    <main class="page join">

        <p class="eyebrow">ペア設定</p>
        <h1 class="page-title">番号を入力</h1>
        <p class="page-lead">相手から聞いた4桁の番号を入れてください</p>

        <form action="{{ route('pair.code.check.post') }}" method="post" data-code-form>
            @csrf

            {{-- 見た目は1桁ずつ。送信は 1つの code にまとめる（JSが同期） --}}
            <div class="code-input" role="group" aria-label="4桁の部屋番号">
                @for ($i = 1; $i <= 4; $i++)
                    <input type="text" class="code-digit"
                           inputmode="numeric" maxlength="1" autocomplete="off"
                           aria-label="{{ $i }}桁目"
                           @if ($i === 1) autofocus @endif
                           data-code-digit>
                @endfor
            </div>

            <input type="hidden" name="code" value="{{ old('code') }}" data-code-value>

            @error('code')
                <p class="join-error" role="alert">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn-primary" data-code-submit disabled>参加する</button>
        </form>

        <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="mascot join-mascot">

    </main>
@endsection

@push('scripts')
    {{-- 画像URLはLaravelに組み立てさせてJSへ渡す（サブディレクトリ配置でも解決するため） --}}
    <script>
        window.POP_IMAGES = [
            "{{ asset('images/IMG_0639.png') }}",
            "{{ asset('images/IMG_0638.png') }}"
        ];
    </script>
    @vite(['resources/js/join.js'])
@endpush
