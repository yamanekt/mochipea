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

        <form action="{{ url('/login') }}" method="post" novalidate
              @if ($loggedOut ?? false) autocomplete="off" @endif>
            @csrf

            <div class="field">
                <label class="field-label" for="email">メールアドレス</label>
                <input type="email" id="email" name="email"
                       value="{{ ($loggedOut ?? false) ? '' : old('email') }}"
                       autocomplete="{{ ($loggedOut ?? false) ? 'off' : 'email' }}"
                       placeholder="you@example.com" required>
            </div>

            <div class="field">
                <span class="field-label">
                    <label for="password">パスワード</label>
                    <button type="button" class="field-toggle"
                            data-toggle-password="password" aria-pressed="false">表示</button>
                </span>
                <input type="password" id="password" name="password"
                       autocomplete="{{ ($loggedOut ?? false) ? 'new-password' : 'current-password' }}"
                       placeholder="password" required>
            </div>

            @if ($errors->any())
                <p class="auth-error" role="alert">
                    {{ $errors->first('login') ?: $errors->first() }}
                </p>
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
    @if ($loggedOut ?? false)
        <script>
            (() => {
                const clearLoginFields = () => {
                    document.getElementById('email').value = '';
                    document.getElementById('password').value = '';
                };

                clearLoginFields();
                window.addEventListener('pageshow', clearLoginFields);
            })();
        </script>
    @endif
@endpush
