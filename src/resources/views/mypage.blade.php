@extends('layouts.app')

@section('title', 'マイページ')

@push('css')
    @vite(['resources/css/mypage.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page mypage">

        {{-- 戦績。登録数は実データ、勝率は勝敗テーブルが無いため暫定のベタ書き --}}
        <section class="battle-stats" aria-label="戦績">
            <div class="battle-stats-text">
                <p class="eyebrow">マイページ</p>

                <div class="stats">
                    <div class="stat">
                        <p class="stat-value">{{ $goalCount }}<span class="stat-unit">件</span></p>
                        <p class="stat-label">登録した目標</p>
                    </div>

                    <span class="stat-divider" aria-hidden="true"></span>

                    <div class="stat">
                        <p class="stat-value stat-value--sub">{{ $winRate }}<span class="stat-unit">%</span></p>
                        <p class="stat-label">勝率</p>
                    </div>
                </div>
            </div>

            <span class="avatar">
                <img src="{{ asset('images/IMG_0638.png') }}" alt="">
            </span>
        </section>

        <div class="card">
            <span class="card-label">アカウント</span>

            <a href="#" class="row">
                <span class="row-label">ユーザー名</span>
                <span class="row-value">
                    <span>{{ $user->name }}</span>
                    <span class="row-chevron" aria-hidden="true">›</span>
                </span>
            </a>

            <a href="#" class="row">
                <span class="row-label">メールアドレス</span>
                <span class="row-value">
                    <span>{{ $user->email }}</span>
                    <span class="row-chevron" aria-hidden="true">›</span>
                </span>
            </a>

            <a href="#" class="row">
                <span class="row-label">パスワード</span>
                <span class="row-value">
                    <span class="row-accent">変更する</span>
                    <span class="row-chevron" aria-hidden="true">›</span>
                </span>
            </a>
        </div>

        {{-- 通知の許可はブラウザの仕様上、ユーザーの操作の中からしか求められない --}}
        <div class="card">
            <span class="card-label">通知</span>

            <button type="button" class="row row-button" data-enable-notification>
                <span class="row-label">通知をオンにする</span>
                <span class="row-chevron" aria-hidden="true">›</span>
            </button>
        </div>

        <div class="card">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="row row-button">
                    <span class="row-label">ログアウト</span>
                    <span class="row-chevron" aria-hidden="true">›</span>
                </button>
            </form>
        </div>

        {{-- 取り返しがつかない操作なので、他から離して単独で置く --}}
        <div class="card card-danger">
            <button type="button" class="row row-button row-danger">
                <span class="row-label">アカウントを削除</span>
                <span class="row-chevron" aria-hidden="true">›</span>
            </button>
        </div>

    </main>
@endsection
