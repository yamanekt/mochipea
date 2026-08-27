<?php

return [
    /*
     | VAPID（プッシュ通知の送信元を証明する鍵）
     |
     | 鍵は次のコマンドで作れる：
     |   php artisan webpush:vapid
     |
     | 出力された2行を .env に貼る。
     | 未設定でもアプリは動く（通知が送られないだけ）。
     */
    'public_key'  => env('VAPID_PUBLIC_KEY'),
    'private_key' => env('VAPID_PRIVATE_KEY'),

    /*
     | 送信元の連絡先。プッシュ配信元（Google など）が問題を見つけたときの
     | 連絡先として使われる。mailto: か https:// で書く。
     */
    'subject' => env('VAPID_SUBJECT', 'mailto:example@example.com'),
];
