<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ルーム作成</title>
    <link rel="stylesheet" href="{{ asset('css/make.css') }}">
</head>
<body>
<div class="bg"></div>

<div class="container">
    <h1>ルーム作成</h1>
    <h2></h2>

    <label>あなたのペアコード</label>

    <div class="password-area">
        <div id="roomCode" class="code-box"></div>

        <button type="button"
                class="share-btn"
                onclick="shareCode()">
            共有
        </button>
    </div>

    <p id="copyMessage"></p>

    <a href="{{ url('/goals') }}" class="main-btn">
        戻る
    </a>
</div>

<script src="{{ asset('js/make.js') }}"></script>
</body>
</html>
