@extends('layouts.guest')

@section('title', 'ログイン')

@push('css')
    @vite(['resources/css/login.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page auth">

        <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="mascot auth-mascot">

        <h1 class="auth-title">おかえりなさい</h1>
        <p class="auth-lead">もちぺあにログイン</p>

        <form action="{{ url('/login') }}" method="post" novalidate>
            @csrf

            <div class="field">
                <label class="field-label" for="email">メールアドレス</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       autocomplete="email" placeholder="you@example.com" required>
            </div>

            <div class="field">
                <span class="field-label">
                    <label for="password">パスワード</label>
                    <button type="button" class="field-toggle"
                            data-toggle-password="password" aria-pressed="false">表示</button>
                </span>
                <input type="password" id="password" name="password"
                       autocomplete="current-password" placeholder="password" required>
            </div>

            {{-- 複数の指摘があっても1件しか出ないと、直しても直しても弾かれる。
                 全件まとめて出す（新規登録画面と同じ見せ方） --}}
            @if ($errors->any())
                <ul class="auth-error-list" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <button type="submit" class="btn-primary">ログイン</button>
        </form>

        {{-- 新規登録は主操作ではないのでテキストリンク。赤は削除・退会にだけ使う --}}
        <p class="auth-switch">
            アカウントをお持ちでない方
            <a href="{{ url('/register') }}">新規登録 →</a>
        </p>

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/auth.js'])
@endpush
