{{-- @extends('layouts.app')

@section('title', 'パスワード変更')

@push('css')
    @vite(['resources/css/mypage.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page account-form-page">

        <a href="{{ route('mypage') }}" class="account-back">← マイページに戻る</a>

        <p class="eyebrow">アカウント</p>
        <h1 class="page-title">パスワード変更</h1>
        <p class="page-lead">英数字8文字以上で設定してください</p>

        @if ($errors->any())
            <ul class="auth-error-list" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('account.password.update') }}" method="post">
            @csrf
            @method('PATCH')

            <div class="field">
                <span class="field-label">
                    <label for="current_password">現在のパスワード</label>
                    <button type="button" class="field-toggle"
                            data-toggle-password="current_password" aria-pressed="false">表示</button>
                </span>
                <input type="password" id="current_password" name="current_password"
                       autocomplete="current-password" placeholder="現在のパスワード" required>
            </div>

            <div class="field">
                <span class="field-label">
                    <label for="password">新しいパスワード</label>
                    <button type="button" class="field-toggle"
                            data-toggle-password="password" aria-pressed="false">表示</button>
                </span>
                <input type="password" id="password" name="password"
                       autocomplete="new-password" placeholder="8文字以上" required>
            </div>

            <div class="field">
                <span class="field-label">
                    <label for="password_confirmation">新しいパスワード（確認）</label>
                    <button type="button" class="field-toggle"
                            data-toggle-password="password_confirmation" aria-pressed="false">表示</button>
                </span>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       autocomplete="new-password" placeholder="もう一度入力" required>
            </div>

            <button type="submit" class="btn-primary">変更する</button>
        </form>

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/auth.js'])
@endpush --}}
