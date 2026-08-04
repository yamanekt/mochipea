<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>部屋番号</title>
    @vite(['resources/css/make.css'])
</head>

<body>
    <div class="bg"></div>
    <div class="container">
        <h1>部屋を作成しました</h1>
        <h2>別のアカウントでログインした相手に、この部屋番号を伝えてください</h2>

        <label>部屋番号</label>
        <div class="password-area">
            <div id="roomCode" class="code-box">{{ $room->room_id }}</div>
            <button type="button" class="share-btn" onclick="shareCode()">共有</button>
        </div>

        <a href="{{ route('pea') }}" class="main-btn">戻る</a>
    </div>

    <script src="{{ asset('js/make.js') }}"></script>
</body>

</html>
