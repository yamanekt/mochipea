@extends('layouts.guest')

@section('title', '新規登録')

@push('css')
    @vite(['resources/css/register.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page auth auth-register">

        <a href="{{ url('/login') }}" class="auth-back" aria-label="ログインへ戻る">←</a>

        <div class="auth-head">
            <div>
                <h1 class="auth-title">はじめまして</h1>
                <p class="auth-lead">アカウントをつくります</p>
            </div>
            <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="auth-head-mascot">
        </div>

        @if ($errors->any())
            <ul class="auth-error-list" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ url('/register') }}" method="post" novalidate>
            @csrf

            <div class="field">
                <label class="field-label" for="name">ユーザー名</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       autocomplete="nickname" placeholder="やまね けいた" required>
            </div>

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
                       autocomplete="new-password" placeholder="8文字以上" required>
            </div>

            <div class="field">
                <span class="field-label">
                    <label for="password_confirmation">パスワード（確認）</label>
                    <button type="button" class="field-toggle"
                            data-toggle-password="password_confirmation" aria-pressed="false">表示</button>
                </span>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       autocomplete="new-password" placeholder="もう一度入力" required>
            </div>

            <button type="submit" class="btn-primary">アカウントをつくる</button>
        </form>

        <p class="auth-switch">
            すでにアカウントをお持ちの方
            <a href="{{ url('/login') }}">ログイン →</a>
        </p>

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/auth.js'])
@endpush
