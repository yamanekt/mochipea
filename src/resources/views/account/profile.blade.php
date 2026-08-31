@extends('layouts.app')

@section('title', 'アカウント情報')

@push('css')
    @vite(['resources/css/mypage.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page account-form-page">

        <a href="{{ route('mypage') }}" class="account-back">← マイページに戻る</a>

        <p class="eyebrow">アカウント</p>
        <h1 class="page-title">アカウント情報</h1>

        @if ($errors->any())
            <ul class="auth-error-list" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('account.profile.update') }}" method="post">
            @csrf
            @method('PATCH')

            <div class="field">
                <label class="field-label" for="name">ユーザー名</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       autocomplete="nickname" placeholder="やまね けいた" required>
            </div>

            <div class="field">
                <label class="field-label" for="email">メールアドレス</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       autocomplete="email" placeholder="you@example.com" required>
            </div>

            <button type="submit" class="btn-primary">保存する</button>
        </form>

    </main>
@endsection
