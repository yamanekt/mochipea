<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タイムライン</title>
    <link rel="stylesheet" href="{{ asset('css/all.css') }}?v={{ filemtime(public_path('css/all.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/timeline.css') }}?v={{ filemtime(public_path('css/timeline.css')) }}">
</head>

<body>

    <div class="bg"></div>

    {{-- 上部のページ切替バー（/current-goals と共通の goals.css のスタイルを使う） --}}
    <header class="page-menu">
        <div class="menu-btn current">タイムライン</div>
        <a href="{{ url('/current-goals') }}" class="menu-btn">目標一覧</a>
    </header>

    {{-- 案内キャラクター --}}
    <div class="guide-character">
        <img src="{{ asset('images/IMG_0639.png') }}" alt="案内キャラクター">
    </div>

    <a href="{{ url('/home') }}" class="back-btn">戻る</a>

    {{-- 達成入力（コメント）を新しい順に並べたタイムライン --}}
    <div class="container">
        @forelse ($entries as $entry)
            <div class="box">
                {{-- 目標名 --}}
                <p class="box-title">目標名：{{ $entry->title }}</p>

                {{-- 投稿者名 ・ 何日前か（Carbonのロケールはapp.phpでjaに設定済みなので「3日前」と表示される） --}}
                <p class="entry-meta">
                    {{ $entry->user_name }}・{{ \Carbon\Carbon::parse($entry->created_at)->diffForHumans() }}
                </p>

                {{-- 今回の回数（達成数）＋単位 --}}
                <p class="entry-count">回数：{{ $entry->value }}{{ $entry->unit }}</p>

                {{-- コメント（memoが未入力ならNULLなので代わりの文言を出す） --}}
                <p class="entry-comment">
                    {{ $entry->memo ?? '（コメントなし）' }}
                </p>

                {{-- その目標の詳細（現在状況）画面へ --}}
                <a href="{{ route('situation.show', $entry->goal_id) }}" class="detail-btn">詳細を見る</a>
            </div>
        @empty
            <div class="box">
                <p>まだ達成入力がありません。</p>
            </div>
        @endforelse
    </div>

</body>

</html>
