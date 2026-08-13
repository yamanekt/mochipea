@extends('layouts.guest')

@section('title', '新規登録')

@push('css')
    @vite(['resources/css/register.css'])
@endpush

@section('content')
<div class="bg"></div>

<div class="page">

    <div class="container panel">

        <a href="{{ url('/login') }}" class="back-btn">戻る</a>

        <img src="{{ asset('images/welcome2.png') }}" alt="welcome" class="top-image">

        <h1>Welcome!</h1>
        <div class="sub-title">新規登録</div>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color:red">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <!-- 👇 フォームエリア -->
        <div class="form-area">

            <form action="{{ url('/register') }}" method="post">
                @csrf

                <label>ユーザー名 *</label>
                <input type="text" name="name" required>

<label for="email">Eメール *</label>
<input type="email" id="email" name="email" autocomplete="email" required>

<label for="password">パスワード *</label>
<input type="password" id="password" name="password" autocomplete="new-password" required>

                <label>パスワード確認 *</label>
                <input type="password" name="password_confirmation" required>

                <button type="submit">
                    アカウント作成
                </button>
            </form>

            <!-- 👇 キャラ（ボタンを指す） -->
            <img src="{{ asset('images/IMG_0639.png') }}" class="point-char">

        </div>

    </div>

</div>
@endsection
