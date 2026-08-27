<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | もちぺあ</title>
    @vite(['resources/css/common.css', 'resources/css/goal-flow.css'])
    @stack('css')
    @include('partials.pwa-head')
</head>
<body class="flow">
    <div class="page-bg"></div>

    {{-- フロー専用のヘッダー。共通ヘッダーとナビは出さない。
         入力途中で他タブへ抜けられると内容が宙に浮くため、中断は ✕ に一本化する --}}
    <header class="flow-header">
        @if ($step > 1)
            <a href="{{ route('goals.new.step', ['step' => $step - 1]) }}"
               class="flow-nav" aria-label="前へ戻る">←</a>
        @else
            <span class="flow-nav flow-nav-off" aria-hidden="true">←</span>
        @endif

        <span class="flow-step">STEP {{ $step }} / 5</span>

        <form action="{{ route('goals.new.cancel') }}" method="post" class="flow-cancel">
            @csrf
            <button type="submit" class="flow-nav" aria-label="作成をやめる">✕</button>
        </form>
    </header>

    <div class="flow-progress" role="progressbar"
         aria-valuenow="{{ $step }}" aria-valuemin="1" aria-valuemax="5"
         aria-label="全5ステップ中 {{ $step }} ステップ目">
        @for ($i = 1; $i <= 5; $i++)
            <span class="flow-seg{{ $i <= $step ? ' is-done' : '' }}"></span>
        @endfor
    </div>

    <main class="flow-body">
        @yield('content')
    </main>

    <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="flow-mascot">

    @vite(['resources/js/pwa.js'])
    @stack('scripts')
</body>
</html>
