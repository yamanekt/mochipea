<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ルーム作成</title>
    <link rel="stylesheet" href="{{ asset('css/make.css') }}">
</head>
<<<<<<< HEAD
<bod
  <div class="bg"></div>
  <div class="container">
    <h1>ルーム作成</h1>
    <h2>パスワード設定</h2>
=======
<body>
<div class="bg"></div>
>>>>>>> 9733eda5e1794c5c4df4c39cafed9ca2d2bb0a2a

<div class="container">
    <h1>ルーム作成</h1>
    <h2></h2>

    <label>あなたのペアコード</label>
    <div class="password-area">
        <div id="roomCode" class="code-box">
            </div> <button type="button" class="share-btn" onclick="shareCode()"> 共有 </button> </div> <form method="POST" action="/create-room"> @csrf <input type="hidden" id="roomNumber" name="roomNumber"> <button type="submit" class="main-btn"> ルーム作成 </button> </form>
  </div>

<script src="{{ asset('js/make.js') }}"></script>
</body>
</html>
