<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * バリデーションで弾かれたあと、入力し直しにならないことを確認する。
 * パスワードは意図的に保持しない（再表示はセキュリティ上のリスクになるため）。
 */
class AuthInputRetentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン失敗後もメールアドレスが残る(): void
    {
        User::factory()->create(['email' => 'me@example.com']);

        $this->post('/login', ['email' => 'me@example.com', 'password' => 'wrong-password'])
            ->assertRedirect()
            ->assertSessionHasErrors('login');

        $this->from('/login')->followingRedirects()
            ->post('/login', ['email' => 'me@example.com', 'password' => 'wrong-password'])
            ->assertSee('value="me@example.com"', false);
    }

    public function test_ログインのバリデーション失敗後もメールアドレスが残る(): void
    {
        $this->from('/login')->followingRedirects()
            ->post('/login', ['email' => 'not-an-email', 'password' => ''])
            ->assertSee('value="not-an-email"', false)
            ->assertSee('パスワードを入力してください。');
    }

    public function test_新規登録の失敗後も名前とメールが残る(): void
    {
        $this->from('/register')->followingRedirects()
            ->post('/register', [
                'name' => 'やまね けいた',
                'email' => 'keita@example.com',
                'password' => 'short',
                'password_confirmation' => 'short',
            ])
            ->assertSee('value="やまね けいた"', false)
            ->assertSee('value="keita@example.com"', false)
            ->assertSee('パスワードは8文字以上にしてください。');
    }

    public function test_メール重複で弾かれても入力が残る(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->from('/register')->followingRedirects()
            ->post('/register', [
                'name' => 'やまね',
                'email' => 'taken@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertSee('value="やまね"', false)
            ->assertSee('value="taken@example.com"', false)
            ->assertSee('このメールアドレスはすでに登録されています。');
    }

    public function test_パスワードは再表示されない(): void
    {
        $this->from('/register')->followingRedirects()
            ->post('/register', [
                'name' => 'やまね',
                'email' => 'bad-email',
                'password' => 'secret12345',
                'password_confirmation' => 'secret12345',
            ])
            ->assertDontSee('secret12345');
    }
}
