<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:vapid';

    protected $description = 'プッシュ通知に使う VAPID 鍵を生成して表示する';

    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $this->newLine();
        $this->info('VAPID鍵を生成しました。下の2行を .env に貼ってください。');
        $this->newLine();
        $this->line('VAPID_PUBLIC_KEY=' . $keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY=' . $keys['privateKey']);
        $this->newLine();
        $this->comment('※ 秘密鍵は公開しないこと。鍵を作り直すと既存の購読は全て無効になります。');
        $this->newLine();

        return self::SUCCESS;
    }
}
