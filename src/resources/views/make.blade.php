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
                @csrf
                <div id="roomCode" class="code-box">
                    {{ $room->room_id }}
                </div> <button type="button" class="share-btn" onclick="shareCode()"> 共有 </button>
            </div>
            <a href="{{ route('pea') }}" class="main-btn">
    ルーム作成
</a>
        </div>

        <script src="{{ asset('js/make.js') }}"></script>
    </body>

</html>
