<?php

namespace Tests\Feature;

use App\Models\PushSubscription;
use App\Models\User;
use App\Services\PushNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private function payload(string $endpoint = 'https://fcm.googleapis.com/fcm/send/abc123'): array
    {
        return [
            'endpoint'  => $endpoint,
            'publicKey' => 'BNcRdreALRFXTkOOUHK1EtK2wtaz5Ry4YfYCA_0QTpQtUbVlUls0VJXg7A8u-Ts1XbjhazAkj7I99e8QcYP7DkM=',
            'authToken' => 'tBHItJI5svbpez7KI4CCXg==',
        ];
    }

    public function test_購読を保存できる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/push/subscribe', $this->payload())
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('push_subscriptions', ['user_id' => $user->id]);
    }

    public function test_同じ端末から何度呼んでも1件しか作らない(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/push/subscribe', $this->payload());
        $this->actingAs($user)->postJson('/push/subscribe', $this->payload());
        $this->actingAs($user)->postJson('/push/subscribe', $this->payload());

        $this->assertDatabaseCount('push_subscriptions', 1);
    }

    public function test_端末ごとに別々に登録される(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/push/subscribe', $this->payload('https://example.com/sub/phone'));
        $this->actingAs($user)->postJson('/push/subscribe', $this->payload('https://example.com/sub/pc'));

        $this->assertDatabaseCount('push_subscriptions', 2);
    }

    public function test_購読を解除できる(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->postJson('/push/subscribe', $this->payload());

        $this->actingAs($user)
            ->postJson('/push/unsubscribe', ['endpoint' => $this->payload()['endpoint']])
            ->assertOk();

        $this->assertDatabaseCount('push_subscriptions', 0);
    }

    public function test_他人の購読は解除できない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($owner)->postJson('/push/subscribe', $this->payload());

        $this->actingAs($other)
            ->postJson('/push/unsubscribe', ['endpoint' => $this->payload()['endpoint']])
            ->assertOk();

        // 持ち主の購読は残ったまま
        $this->assertDatabaseCount('push_subscriptions', 1);
    }

    public function test_未ログインでは購読できない(): void
    {
        $this->postJson('/push/subscribe', $this->payload())->assertUnauthorized();
    }

    public function test_不正な入力は弾かれる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/push/subscribe', ['endpoint' => ''])
            ->assertStatus(422);
    }

    public function test_VAPID鍵が未設定なら送信しない(): void
    {
        config(['webpush.public_key' => null, 'webpush.private_key' => null]);

        $user = User::factory()->create();
        PushSubscription::create([
            'user_id' => $user->id,
            'endpoint' => 'https://example.com/sub',
            'endpoint_hash' => PushSubscription::hashEndpoint('https://example.com/sub'),
            'public_key' => 'x',
            'auth_token' => 'y',
        ]);

        $notifier = new PushNotifier();

        $this->assertFalse($notifier->isConfigured());
        // 鍵が無くても例外にならず、0件で返る
        $this->assertSame(0, $notifier->sendToUser($user, 'タイトル', '本文'));
    }

    public function test_購読が無ければ送信しない(): void
    {
        config([
            'webpush.public_key' => 'dummy-public',
            'webpush.private_key' => 'dummy-private',
        ]);

        $user = User::factory()->create();

        $this->assertSame(0, (new PushNotifier())->sendToUser($user, 'タイトル', '本文'));
    }
}
