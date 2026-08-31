<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_マイページの各行が実際のページに繋がっている(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/mypage')
            ->assertOk()
            ->assertSee(route('account.profile'), false)
            ->assertSee(route('account.password'), false)
            ->assertSee(route('account.delete'), false)
            ->assertDontSee('href="#"', false);
    }

    public function test_名前とメールを変更できる(): void
    {
        $user = User::factory()->create(['name' => '旧名前', 'email' => 'old@example.com']);

        $this->actingAs($user)
            ->patch('/account/profile', ['name' => '新しい名前', 'email' => 'new@example.com'])
            ->assertRedirect(route('mypage'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => '新しい名前', 'email' => 'new@example.com']);
    }

    public function test_他人が使っているメールには変更できない(): void
    {
        $user = User::factory()->create(['email' => 'me@example.com']);
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($user)
            ->from('/account/profile')
            ->patch('/account/profile', ['name' => 'やまね', 'email' => 'taken@example.com'])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'me@example.com']);
    }

    public function test_自分の今のメールならそのまま保存できる(): void
    {
        $user = User::factory()->create(['email' => 'me@example.com']);

        $this->actingAs($user)
            ->patch('/account/profile', ['name' => '新しい名前', 'email' => 'me@example.com'])
            ->assertRedirect(route('mypage'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => '新しい名前']);
    }

    public function test_パスワードを変更できる(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword1')]);

        $this->actingAs($user)
            ->patch('/account/password', [
                'current_password' => 'oldpassword1',
                'password' => 'newpassword1',
                'password_confirmation' => 'newpassword1',
            ])
            ->assertRedirect(route('mypage'));

        $this->assertTrue(Hash::check('newpassword1', $user->fresh()->password));
    }

    public function test_現在のパスワードが違えば変更できない(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword1')]);

        $this->actingAs($user)
            ->from('/account/password')
            ->patch('/account/password', [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword1',
                'password_confirmation' => 'newpassword1',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('oldpassword1', $user->fresh()->password));
    }

    public function test_退会できる(): void
    {
        $user = User::factory()->create(['password' => Hash::make('mypassword1')]);

        $this->actingAs($user)
            ->delete('/account', ['password' => 'mypassword1'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertGuest();
    }

    public function test_パスワードが違えば退会できない(): void
    {
        $user = User::factory()->create(['password' => Hash::make('mypassword1')]);

        $this->actingAs($user)
            ->from('/account/delete')
            ->delete('/account', ['password' => 'wrongpassword'])
            ->assertSessionHasErrors('password');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_退会画面に何が消えるか書いてある(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/account/delete')
            ->assertOk()
            ->assertSee('元に戻せません')
            ->assertSee('登録した目標');
    }
}
