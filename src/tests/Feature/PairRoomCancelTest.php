<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PairRoomCancelTest extends TestCase
{
    use RefreshDatabase;

    /** 目標作成フローを通して、待機中の部屋を1件作る */
    private function createWaitingRoom(User $user): string
    {
        $this->actingAs($user)
            ->withSession(['goal_draft' => [
                'category'     => 'study',
                'title'        => '英単語を覚える',
                'target_value' => 100,
                'unit'         => '語',
                'deadline'     => now()->addMonth()->toDateString(),
            ]])
            ->post('/goals/new');

        return DB::table('pair_codes')->where('user_id', $user->id)->value('code');
    }

    public function test_待機中の部屋を取り消すと4テーブルから消える(): void
    {
        $user = User::factory()->create();
        $code = $this->createWaitingRoom($user);

        $this->assertDatabaseCount('goals', 1);

        $this->actingAs($user)
            ->from('/pea/waiting')
            ->post('/pea/waiting/cancel', ['code' => $code])
            ->assertRedirect(route('pair.waiting'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('goals', 0);
        $this->assertDatabaseCount('pairs', 0);
        $this->assertDatabaseMissing('pair_codes', ['code' => $code]);
        $this->assertDatabaseMissing('rooms', ['room_id' => $code]);
    }

    public function test_他人の部屋は取り消せない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $code = $this->createWaitingRoom($owner);

        $this->actingAs($other)
            ->from('/pea/waiting')
            ->post('/pea/waiting/cancel', ['code' => $code])
            ->assertSessionHasErrors('cancel');

        // 持ち主の目標は残ったまま
        $this->assertDatabaseCount('goals', 1);
        $this->assertDatabaseHas('pair_codes', ['code' => $code, 'status' => 'waiting']);
    }

    public function test_ペア成立済みの部屋は取り消せない(): void
    {
        $owner = User::factory()->create();
        $joiner = User::factory()->create();
        $code = $this->createWaitingRoom($owner);

        // 相手が参加してペア成立
        $this->actingAs($joiner)->post('/pair-code-check', ['code' => $code]);
        $this->assertDatabaseHas('pair_codes', ['code' => $code, 'status' => 'matched']);

        $this->actingAs($owner)
            ->from('/pea/waiting')
            ->post('/pea/waiting/cancel', ['code' => $code])
            ->assertSessionHasErrors('cancel');

        $this->assertDatabaseCount('goals', 1);
    }

    public function test_一覧に取り消しボタンが出る(): void
    {
        $user = User::factory()->create();
        $code = $this->createWaitingRoom($user);

        $this->actingAs($user)->get('/pea/waiting')
            ->assertOk()
            ->assertSee($code)
            ->assertSee('取り消す')
            ->assertSee(route('pair.waiting.cancel'), false);
    }
}
