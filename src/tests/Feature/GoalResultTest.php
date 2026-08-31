<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GoalResultTest extends TestCase
{
    use RefreshDatabase;

    /** 成立済みのペアと目標を1件つくる */
    private function makeGoal(User $owner, User $partner, array $override = []): Goal
    {
        $pairId = DB::table('pairs')->insertGetId([
            'user1_id' => $owner->id,
            'user2_id' => $partner->id,
            'pair_code' => '1234',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return Goal::create(array_merge([
            'pair_id' => $pairId,
            'category' => 'study',
            'mode' => Goal::MODE_ACCUMULATE,
            'title' => '英単語を覚える',
            'target_value' => 1000,
            'unit' => '語',
            'deadline' => now()->addWeek()->toDateString(),
        ], $override));
    }

    private function record(Goal $goal, User $user, int $value): void
    {
        GoalProgress::create([
            'goal_id' => $goal->id,
            'user_id' => $user->id,
            'value' => $value,
            'progress_date' => now()->toDateString(),
        ]);
    }

    public function test_期限前は勝敗が確定しない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->makeGoal($owner, $partner);
        $this->record($goal, $owner, 500);

        $this->actingAs($owner)->get("/situation/{$goal->id}")->assertOk();

        $this->assertNull($goal->fresh()->result);
    }

    public function test_期限を過ぎると勝敗が確定する(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->makeGoal($owner, $partner, ['deadline' => now()->subDay()->toDateString()]);

        $this->record($goal, $owner, 600);
        $this->record($goal, $partner, 400);

        $this->actingAs($owner)->get("/situation/{$goal->id}")
            ->assertOk()
            ->assertSee('勝ち');

        $fresh = $goal->fresh();
        $this->assertSame(Goal::RESULT_WIN, $fresh->result);
        $this->assertSame('finished', $fresh->status);
        $this->assertNotNull($fresh->finished_at);
    }

    public function test_相手から見ると結果が反転する(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->makeGoal($owner, $partner, ['deadline' => now()->subDay()->toDateString()]);

        $this->record($goal, $owner, 600);
        $this->record($goal, $partner, 400);

        // 作成者は勝ち
        $this->actingAs($owner)->get("/situation/{$goal->id}")->assertSee('勝ち');
        // 相手は負け（DBには作成者視点の win が入ったまま）
        $this->actingAs($partner)->get("/situation/{$goal->id}")->assertSee('負け');

        $this->assertSame(Goal::RESULT_WIN, $goal->fresh()->result);
    }

    public function test_同点なら引き分け(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->makeGoal($owner, $partner, ['deadline' => now()->subDay()->toDateString()]);

        $this->record($goal, $owner, 500);
        $this->record($goal, $partner, 500);

        $this->actingAs($owner)->get("/situation/{$goal->id}")->assertSee('引き分け');
        $this->assertSame(Goal::RESULT_DRAW, $goal->fresh()->result);
    }

    public function test_一度確定した勝敗は上書きされない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->makeGoal($owner, $partner, ['deadline' => now()->subDay()->toDateString()]);

        $this->record($goal, $owner, 600);
        $this->record($goal, $partner, 400);
        $this->actingAs($owner)->get("/situation/{$goal->id}");

        $finishedAt = $goal->fresh()->finished_at;

        // 期限後に相手が追加記録しても結果は変わらない
        $this->record($goal, $partner, 9999);
        $this->actingAs($owner)->get("/situation/{$goal->id}")->assertSee('勝ち');

        $this->assertSame(Goal::RESULT_WIN, $goal->fresh()->result);
        $this->assertEquals($finishedAt, $goal->fresh()->finished_at);
    }

    public function test_目標を超えたら超過分が出る(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->makeGoal($owner, $partner);

        $this->record($goal, $owner, 1200);

        $this->actingAs($owner)->get("/situation/{$goal->id}")
            ->assertOk()
            ->assertSee('目標達成！さらに 200語 上乗せ中');
    }

    public function test_サバイバルではHPが表示される(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->makeGoal($owner, $partner, ['mode' => Goal::MODE_SURVIVAL]);

        $this->record($goal, $owner, 300);

        $this->actingAs($owner)->get("/situation/{$goal->id}")
            ->assertOk()
            ->assertSee('相手の残りHP')
            ->assertSee('>700<', false)    // 自分が300削った
            ->assertSee('>1000<', false)   // 相手はまだ削っていない
            ->assertDontSee('達成率');     // サバイバルでは達成率を出さない
    }
}
