<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NoticeTest extends TestCase
{
    use RefreshDatabase;

    private function pairedGoal(User $owner, User $partner, array $override = []): Goal
    {
        $pairId = DB::table('pairs')->insertGetId([
            'user1_id' => $owner->id,
            'user2_id' => $partner->id,
            'pair_code' => (string) random_int(1000, 9999),
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

    public function test_ヘッダーのお知らせが繋がっている(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/mypage')
            ->assertOk()
            ->assertSee(route('notices'), false);
    }

    public function test_相手の記録がお知らせに出る(): void
    {
        $me = User::factory()->create();
        $partner = User::factory()->create(['name' => 'はなこ']);
        $goal = $this->pairedGoal($me, $partner);

        GoalProgress::create([
            'goal_id' => $goal->id, 'user_id' => $partner->id,
            'value' => 30, 'memo' => '今日は集中できた', 'progress_date' => now()->toDateString(),
        ]);

        $this->actingAs($me)->get('/notices')
            ->assertOk()
            ->assertSee('はなこさんが30語記録しました')
            ->assertSee('今日は集中できた');
    }

    public function test_自分の記録はお知らせに出ない(): void
    {
        $me = User::factory()->create(['name' => 'じぶん']);
        $partner = User::factory()->create();
        $goal = $this->pairedGoal($me, $partner);

        GoalProgress::create([
            'goal_id' => $goal->id, 'user_id' => $me->id,
            'value' => 50, 'progress_date' => now()->toDateString(),
        ]);

        $this->actingAs($me)->get('/notices')
            ->assertOk()
            ->assertDontSee('じぶんさんが50語記録しました');
    }

    public function test_サバイバルは削ったと表示される(): void
    {
        $me = User::factory()->create();
        $partner = User::factory()->create(['name' => 'はなこ']);
        $goal = $this->pairedGoal($me, $partner, ['mode' => Goal::MODE_SURVIVAL]);

        GoalProgress::create([
            'goal_id' => $goal->id, 'user_id' => $partner->id,
            'value' => 30, 'progress_date' => now()->toDateString(),
        ]);

        $this->actingAs($me)->get('/notices')
            ->assertOk()
            ->assertSee('はなこさんが30語削りました');
    }

    public function test_決着した目標がお知らせに出る(): void
    {
        $me = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->pairedGoal($me, $partner);
        $goal->forceFill(['result' => Goal::RESULT_WIN, 'finished_at' => now(), 'status' => 'finished'])->save();

        $this->actingAs($me)->get('/notices')
            ->assertOk()
            ->assertSee('決着しました：勝ち');
    }

    public function test_相手側では決着の勝敗が反転する(): void
    {
        $me = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->pairedGoal($me, $partner);
        $goal->forceFill(['result' => Goal::RESULT_WIN, 'finished_at' => now(), 'status' => 'finished'])->save();

        $this->actingAs($partner)->get('/notices')
            ->assertOk()
            ->assertSee('決着しました：負け');
    }

    public function test_他人の対戦は出ない(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create(['name' => 'たにん']);
        $stranger = User::factory()->create();
        $goal = $this->pairedGoal($a, $b);

        GoalProgress::create([
            'goal_id' => $goal->id, 'user_id' => $b->id,
            'value' => 30, 'progress_date' => now()->toDateString(),
        ]);

        $this->actingAs($stranger)->get('/notices')
            ->assertOk()
            ->assertDontSee('たにんさんが30語記録しました')
            ->assertSee('お知らせはまだありません');
    }
}
