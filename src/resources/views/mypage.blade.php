<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ | MochiPea</title>
    @vite(['resources/css/common.css','resources/css/mypage.css'])
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

        <section class="profile-card" aria-labelledby="profile-title">


            <h3 id="profile-title">プロフィール</h3>

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
                    <dd class="password-value" aria-label="パスワード"></dd>
                </div>
            </dl>

            <a href="#" class="password-link">
                <span>パスワード変更はこちら</span>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            </a>

            <div class="danger-actions" aria-labelledby="danger-actions-title">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="danger-button">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                        <span>ログアウト</span>
                    </button>
                </form>

                <button type="button" class="danger-button danger-button--delete">
                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                    <span>アカウントを削除</span>
                </button>
            </div>
        </section>
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

        <a href="{{ route('mypage') }}" class="nav-item active" aria-current="page">
            <i class="fa-regular fa-user"></i>
            <span>マイページ</span>
        </a>
    </nav>
</body>
</html>
