<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GoalResultHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function finishedGoal(User $owner, User $partner, string $result, string $title, int $mine, int $theirs): Goal
    {
        $pairId = DB::table('pairs')->insertGetId([
            'user1_id' => $owner->id,
            'user2_id' => $partner->id,
            'pair_code' => (string) random_int(1000, 9999),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $goal = Goal::create([
            'pair_id' => $pairId,
            'category' => 'study',
            'mode' => Goal::MODE_ACCUMULATE,
            'title' => $title,
            'target_value' => 1000,
            'unit' => '語',
            'deadline' => now()->subDay()->toDateString(),
        ]);

        $goal->forceFill(['status' => 'finished', 'result' => $result, 'finished_at' => now()])->save();

        GoalProgress::create(['goal_id' => $goal->id, 'user_id' => $owner->id, 'value' => $mine, 'progress_date' => now()->toDateString()]);
        GoalProgress::create(['goal_id' => $goal->id, 'user_id' => $partner->id, 'value' => $theirs, 'progress_date' => now()->toDateString()]);

        return $goal;
    }

    public function test_決着した目標が一覧に出る(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create(['name' => 'はなこ']);
        $this->finishedGoal($owner, $partner, Goal::RESULT_WIN, '英単語を覚える', 600, 400);

        $this->actingAs($owner)->get('/results')
            ->assertOk()
            ->assertSee('英単語を覚える')
            ->assertSee('はなこ')
            ->assertSee('勝ち')
            ->assertSee('600語')
            ->assertSee('400語');
    }

    public function test_相手側では勝敗が反転して出る(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $this->finishedGoal($owner, $partner, Goal::RESULT_WIN, '英単語を覚える', 600, 400);

        $this->actingAs($partner)->get('/results')
            ->assertOk()
            ->assertSee('負け')
            ->assertSee('400語')
            ->assertSee('600語');
    }

    public function test_通算成績が集計される(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();

        $this->finishedGoal($owner, $partner, Goal::RESULT_WIN,  '目標A', 600, 400);
        $this->finishedGoal($owner, $partner, Goal::RESULT_WIN,  '目標B', 700, 300);
        $this->finishedGoal($owner, $partner, Goal::RESULT_LOSE, '目標C', 200, 800);
        $this->finishedGoal($owner, $partner, Goal::RESULT_DRAW, '目標D', 500, 500);

        $response = $this->actingAs($owner)->get('/results')->assertOk();

        $html = $response->getContent();
        // 勝ち2 / 負け1 / 引き分け1
        $this->assertMatchesRegularExpression('/<strong>2<\/strong>\s*<span>勝ち/u', $html);
        $this->assertMatchesRegularExpression('/<strong>1<\/strong>\s*<span>負け/u', $html);
        $this->assertMatchesRegularExpression('/<strong>1<\/strong>\s*<span>引き分け/u', $html);
    }

    public function test_未決着の目標は出ない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();

        $pairId = DB::table('pairs')->insertGetId([
            'user1_id' => $owner->id, 'user2_id' => $partner->id,
            'pair_code' => '1234', 'created_at' => now(), 'updated_at' => now(),
        ]);
        Goal::create([
            'pair_id' => $pairId, 'category' => 'study', 'mode' => Goal::MODE_ACCUMULATE,
            'title' => 'まだ進行中', 'target_value' => 100, 'unit' => '語',
            'deadline' => now()->addWeek()->toDateString(),
        ]);

        $this->actingAs($owner)->get('/results')
            ->assertOk()
            ->assertDontSee('まだ進行中')
            ->assertSee('まだ決着した目標がありません');
    }

    public function test_他人の対戦は出ない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $stranger = User::factory()->create();

        $this->finishedGoal($owner, $partner, Goal::RESULT_WIN, '他人の目標', 600, 400);

        $this->actingAs($stranger)->get('/results')
            ->assertOk()
            ->assertDontSee('他人の目標');
    }

    public function test_目標一覧から履歴へ行ける(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/current-goals')
            ->assertOk()
            ->assertSee(route('goal.results'), false)
            ->assertSee('対戦履歴を見る');
    }
}
