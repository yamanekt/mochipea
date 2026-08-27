<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ホームは認証必須なので、未ログインならログイン画面へ送られる。
     * （Laravel の初期テンプレートは 200 を期待していたが、
     *   auth ミドルウェアを付けた時点から実態と合わなくなっていた）
     */
    public function test_未ログインならログイン画面へ送られる(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_ログインしていればホームが開く(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertOk();
    }
}
