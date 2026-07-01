<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ルーム作成</title>
    <link rel="stylesheet" href="{{ asset('css/make.css') }}">
</head>

<body>


    <div class="bg"></div>
<div class="rolling-area">

    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r1">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r2">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r3">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r4">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r5">
    <img src="{{ asset('images/IMG_0639.png') }}" class="rolling r6">
    <img src="{{ asset('images/IMG_0638.png') }}" class="rolling r7">

</div>


        <div class="container">
            <h1>ルーム作成</h1>
            <h2></h2>

            <label>あなたのペアコード</label>
            <div class="password-area">
                <div id="roomCode" class="code-box">
                </div> <button type="button" class="share-btn" onclick="shareCode()"> 共有 </button>
            </div>
            <a href="{{ route('pea') }}" class="main-btn">
    ペア設定に戻る
</a>
        </div>

        <script src="{{ asset('js/make.js') }}"></script>
    </body>

</html>
