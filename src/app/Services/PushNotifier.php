<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/**
 * プッシュ通知の送信。
 *
 * VAPID の鍵が未設定なら「何もしない」。
 * 通知が送れないことでアプリ本体の処理が止まらないようにするため、
 * 例外は投げずログに残すだけにしている。
 */
class PushNotifier
{
    public function isConfigured(): bool
    {
        return filled(config('webpush.public_key')) && filled(config('webpush.private_key'));
    }

    /**
     * 相手ユーザーの全端末へ送る。
     *
     * @return int 送信できた端末数
     */
    public function sendToUser(User $user, string $title, string $body, string $url = '/'): int
    {
        if (! $this->isConfigured()) {
            Log::info('VAPID鍵が未設定のため通知を送りませんでした', ['user_id' => $user->id]);
            return 0;
        }

        $subscriptions = PushSubscription::where('user_id', $user->id)->get();

        if ($subscriptions->isEmpty()) {
            return 0;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject'    => config('webpush.subject'),
                'publicKey'  => config('webpush.public_key'),
                'privateKey' => config('webpush.private_key'),
            ],
        ]);

        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
        ], JSON_UNESCAPED_UNICODE);

        foreach ($subscriptions as $subscription) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint'  => $subscription->endpoint,
                    'publicKey' => $subscription->public_key,
                    'authToken' => $subscription->auth_token,
                ]),
                $payload
            );
        }

        $sent = 0;

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $sent++;
                continue;
            }

            // 端末側で通知を切った・アプリを消した場合は購読が無効になる。
            // 残しておいても二度と届かないので削除する
            if ($report->isSubscriptionExpired()) {
                PushSubscription::where('endpoint_hash', PushSubscription::hashEndpoint($report->getEndpoint()))
                    ->delete();
                continue;
            }

            Log::warning('プッシュ通知の送信に失敗しました', [
                'endpoint' => $report->getEndpoint(),
                'reason'   => $report->getReason(),
            ]);
        }

        return $sent;
    }
}
