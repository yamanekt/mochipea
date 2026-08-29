@extends('layouts.app')

@section('title', 'アカウントの削除')

@push('css')
    @vite(['resources/css/mypage.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page account-form-page">

        <a href="{{ route('mypage') }}" class="account-back">← マイページに戻る</a>

        <p class="eyebrow">アカウント</p>
        <h1 class="page-title">アカウントを削除</h1>

        @if ($errors->any())
            <ul class="auth-error-list" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if (! $deletionEnabled)
            {{-- デモ期間中は削除を受け付けない。
                 config/demo.php（= .env の ACCOUNT_DELETION_ENABLED）で切り替わる --}}
            <div class="card deletion-paused">
                <p class="deletion-paused-title">いまは削除できません</p>
                <p>{{ $notice }}</p>
                <p class="deletion-paused-sub">
                    デモ期間が終わったら削除できるようになります。
                    アカウントを使うのをやめたいだけなら、ログアウトしてそのままにしておいて問題ありません。
                </p>
            </div>

            <img src="{{ asset('images/IMG_0639.png') }}" alt="" class="mascot deletion-mascot">

            <a href="{{ route('mypage') }}" class="btn-primary">マイページに戻る</a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-text">ログアウトする</button>
            </form>
        @else
            {{-- 取り返しがつかないので、何が消えるかを先に伝える --}}
            <div class="card delete-warning">
                <p><strong>削除すると元に戻せません。</strong></p>
                <ul>
                    <li>登録した目標と、これまでの記録</li>
                    <li>ペアの相手とのつながり</li>
                    <li>対戦履歴</li>
                </ul>
                <p>すべて削除され、復元できません。</p>
            </div>

            <form action="{{ route('account.destroy') }}" method="post"
                  data-confirm="本当にアカウントを削除しますか？この操作は取り消せません。">
                @csrf
                @method('DELETE')

                <div class="field">
                    <span class="field-label">
                        <label for="password">確認のためパスワードを入力</label>
                        <button type="button" class="field-toggle"
                                data-toggle-password="password" aria-pressed="false">表示</button>
                    </span>
                    <input type="password" id="password" name="password"
                           autocomplete="current-password" placeholder="パスワード" required>
                </div>

                <button type="submit" class="btn-danger">アカウントを削除する</button>
            </form>
        @endif

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/auth.js', 'resources/js/pair-waiting.js'])
@endpush
