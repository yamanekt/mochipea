<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WelcomeMailTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $override = []): array
    {
        return array_merge([
            'name' => 'やまね けいた',
            'email' => 'keita@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $override);
    }

    public function test_登録すると完了メールが送られる(): void
    {
        Mail::fake();

        $this->post('/register', $this->validPayload())->assertRedirect(route('login'));

        Mail::assertSent(WelcomeMail::class, function (WelcomeMail $mail) {
            return $mail->hasTo('keita@example.com')
                && $mail->user->name === 'やまね けいた';
        });
    }

    public function test_登録に失敗したときはメールを送らない(): void
    {
        Mail::fake();

        $this->from('/register')
            ->post('/register', $this->validPayload(['password' => 'short', 'password_confirmation' => 'short']));

        Mail::assertNothingSent();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_メール送信に失敗しても登録は成功する(): void
    {
        // 送信時に例外を投げさせ、登録処理が巻き込まれないことを確認する
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/register', $this->validPayload())->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', ['email' => 'keita@example.com']);
    }

    public function test_メール本文に名前とアプリ名が入る(): void
    {
        $user = User::factory()->create(['name' => 'はなこ']);

        $rendered = (new WelcomeMail($user))->render();

        $this->assertStringContainsString('はなこ', $rendered);
        $this->assertStringContainsString('部屋番号', $rendered);
    }
}
