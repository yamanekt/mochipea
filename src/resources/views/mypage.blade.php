<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    @vite(['resources/css/common.css'])
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 70px 20px 110px;
            font-family: "Noto Sans JP", sans-serif;
            color: #333;
            background: #fff;
        }

        .mypage {
            width: min(100%, 480px);
            margin: 70px auto 0;
        }

        .profile-list {
            display: grid;
            gap: 18px;
            margin: 0;
        }

        .profile-row {
            display: grid;
            grid-template-columns: 135px minmax(0, 1fr);
            align-items: center;
            gap: 16px;
        }

        .profile-row dt {
            text-align: right;
            font-size: 17px;
            white-space: nowrap;
        }

        .profile-row dd {
            min-height: 40px;
            margin: 0;
            padding: 7px 10px;
            border: 1px solid #555;
            background: #fff;
            font-size: 18px;
        }

        .password-link {
            display: block;
            width: fit-content;
            margin: 20px 0 0 151px;
            color: #287db8;
            font-size: 16px;
            text-decoration: none;
        }

        .password-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 520px) {
            body {
                padding-inline: 14px;
            }

            .mypage {
                margin-top: 45px;
            }

            .profile-row {
                grid-template-columns: 112px minmax(0, 1fr);
                gap: 10px;
            }

            .profile-row dt {
                font-size: 15px;
            }

            .profile-row dd {
                min-height: 38px;
            }

            .password-link {
                margin-left: 122px;
                font-size: 15px;
            }
        }
    </style>
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
