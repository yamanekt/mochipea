<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    @vite(['resources/css/common.css'])
    @vite(['resources/css/mypage.css'])
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <header class="top-header">
        <h1 class="logo">MochiPea</h1>

        <div class="header-right">
            <a href="#" class="icon-btn notice">
                <i class="fa-regular fa-bell"></i>
                <small>お知らせ</small>
            </a>
        </div>
    </header>

    <main class="mypage">
        <dl class="profile-list">
            <div class="profile-row">
                <dt>ユーザー名</dt>
                <dd aria-label="ユーザー名"></dd>
            </div>

            <div class="profile-row">
                <dt>メールアドレス</dt>
                <dd aria-label="メールアドレス"></dd>
            </div>

            <div class="profile-row">
                <dt>パスワード</dt>
                <dd aria-label="パスワード"></dd>
            </div>
        </dl>

        <a href="#" class="password-link">パスワード変更はこちら</a>
    </main>

    <nav class="bottom-nav">
        <a href="{{ url('/home') }}" class="nav-item">
            <i class="fa-solid fa-house"></i>
            <span>ホーム</span>
        </a>

        <a href="{{ route('current-goals') }}" class="nav-item">
            <i class="fa-solid fa-list-check"></i>
            <span>目標一覧</span>
        </a>

        <a href="{{ url('/pea') }}" class="nav-item">
            <i class="fa-solid fa-circle-plus"></i>
            <span>目標作成</span>
        </a>

        <a href="{{ url('/timeline') }}" class="nav-item">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>タイムライン</span>
        </a>

        <a href="{{ route('mypage') }}" class="nav-item active">
            <i class="fa-regular fa-user"></i>
            <span>マイページ</span>
        </a>
    </nav>
</body>
</html>
