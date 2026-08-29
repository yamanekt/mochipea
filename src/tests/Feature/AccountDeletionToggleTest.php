<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * デモでアカウントを配布する期間だけ、削除を止められること。
 * config/demo.php（= .env の ACCOUNT_DELETION_ENABLED）1箇所で切り替わる。
 */
class AccountDeletionToggleTest extends TestCase
{
    use RefreshDatabase;

    private function pause(): void
    {
        config(['demo.account_deletion_enabled' => false]);
    }

    private function allow(): void
    {
        config(['demo.account_deletion_enabled' => true]);
    }

    public function test_停止中は削除画面が案内に切り替わる(): void
    {
        $this->pause();

        $this->actingAs(User::factory()->create())
            ->get('/account/delete')
            ->assertOk()
            ->assertSee('いまは削除できません')
            ->assertSee('デモ期間中')
            ->assertDontSee('アカウントを削除する')   // 削除ボタンは出ない
            ->assertDontSee('元に戻せません');        // 警告文も出ない
    }

    public function test_停止中でも画面自体は残っている(): void
    {
        $this->pause();

        // 404 にせず、理由が読める画面を出す
        $this->actingAs(User::factory()->create())
            ->get('/account/delete')
            ->assertOk()
            ->assertSee('アカウントを削除');
    }

    public function test_停止中は直接POSTしても削除されない(): void
    {
        $this->pause();

        $user = User::factory()->create(['password' => Hash::make('mypassword1')]);

        // 画面を隠すだけでは防げないので、サーバー側でも止まることを確認する
        $this->actingAs($user)
            ->from('/account/delete')
            ->delete('/account', ['password' => 'mypassword1'])
            ->assertRedirect(route('account.delete'))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertAuthenticated();
    }

    public function test_マイページに停止中と出る(): void
    {
        $this->pause();

        $this->actingAs(User::factory()->create())
            ->get('/mypage')
            ->assertOk()
            ->assertSee('停止中')
            ->assertSee(route('account.delete'), false);
    }

    public function test_解除すれば削除できる(): void
    {
        $this->allow();

        $user = User::factory()->create(['password' => Hash::make('mypassword1')]);

        $this->actingAs($user)
            ->get('/account/delete')
            ->assertOk()
            ->assertSee('元に戻せません')
            ->assertDontSee('いまは削除できません');

        $this->actingAs($user)
            ->delete('/account', ['password' => 'mypassword1'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_環境変数が無ければ既定で削除できる(): void
    {
        // .env に ACCOUNT_DELETION_ENABLED を書かなければ true（通常運用）になる。
        // 環境変数を一時的に外して、config/demo.php の既定値を確かめる
        $original = $_ENV['ACCOUNT_DELETION_ENABLED'] ?? null;
        unset($_ENV['ACCOUNT_DELETION_ENABLED'], $_SERVER['ACCOUNT_DELETION_ENABLED']);
        putenv('ACCOUNT_DELETION_ENABLED');

        try {
            $default = require config_path('demo.php');

            $this->assertTrue(
                $default['account_deletion_enabled'],
                '環境変数が未設定のときは削除できる状態が既定であること'
            );
        } finally {
            if ($original !== null) {
                $_ENV['ACCOUNT_DELETION_ENABLED'] = $original;
                putenv("ACCOUNT_DELETION_ENABLED={$original}");
            }
        }
    }
}
