<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * 目標IDを直接指定して他人のデータに触れないことを確かめる。
 *
 * 詳細・達成入力はどちらもURLやhiddenで goal_id を受け取るだけなので、
 * 絞り込みを外すと総当たりで他人の記録が読める／書けるようになる。
 */
class GoalAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** 2人のペアと、その目標を1件つくる */
    private function pairedGoal(User $owner, User $partner): Goal
    {
        $pairId = DB::table('pairs')->insertGetId([
            'user1_id' => $owner->id,
            'user2_id' => $partner->id,
            'pair_code' => (string) random_int(1000, 9999),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return Goal::create([
            'pair_id' => $pairId,
            'category' => 'study',
            'mode' => Goal::MODE_ACCUMULATE,
            'title' => '英単語を覚える',
            'target_value' => 1000,
            'unit' => '語',
            'deadline' => now()->addMonth()->toDateString(),
        ]);
    }

    public function test_ペアの当事者は目標の詳細を開ける(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->pairedGoal($owner, $partner);

        $this->actingAs($owner)->get("/situation/{$goal->id}")->assertOk();
        $this->actingAs($partner)->get("/situation/{$goal->id}")->assertOk();
    }

    public function test_無関係な利用者は目標の詳細を開けない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $outsider = User::factory()->create();
        $goal = $this->pairedGoal($owner, $partner);

        $this->actingAs($outsider)->get("/situation/{$goal->id}")->assertNotFound();
    }

    public function test_無関係な利用者は達成入力画面を開けない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $outsider = User::factory()->create();
        $goal = $this->pairedGoal($owner, $partner);

        $this->actingAs($outsider)->get("/progress/{$goal->id}")->assertNotFound();
    }

    public function test_無関係な利用者は他人の目標に記録できない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $outsider = User::factory()->create();
        $goal = $this->pairedGoal($owner, $partner);

        $this->actingAs($outsider)->post('/progress', [
            'goal_id' => $goal->id,
            'value' => 50,
            'progress_date' => now()->toDateString(),
        ])->assertNotFound();

        $this->assertDatabaseCount('goal_progress', 0);
    }

    public function test_達成入力の現在値に相手の記録は含まれない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->pairedGoal($owner, $partner);

        GoalProgress::create(['goal_id' => $goal->id, 'user_id' => $owner->id, 'value' => 7, 'progress_date' => now()->toDateString()]);
        GoalProgress::create(['goal_id' => $goal->id, 'user_id' => $partner->id, 'value' => 20, 'progress_date' => now()->toDateString()]);

        $response = $this->actingAs($owner)->get("/progress/{$goal->id}")->assertOk();

        // 27（両者の合計）ではなく、自分の 7 が出る
        $this->assertSame(7, (int) $response->viewData('current'));
    }

    public function test_未来の日付では記録できない(): void
    {
        $owner = User::factory()->create();
        $partner = User::factory()->create();
        $goal = $this->pairedGoal($owner, $partner);

        $this->actingAs($owner)
            ->from("/progress/{$goal->id}")
            ->post('/progress', [
                'goal_id' => $goal->id,
                'value' => 10,
                'progress_date' => now()->addDay()->toDateString(),
            ])
            ->assertSessionHasErrors('progress_date');

        $this->assertDatabaseCount('goal_progress', 0);
    }

    public function test_ログインの連続失敗は回数制限で止まる(): void
    {
        $user = User::factory()->create(['email' => 'taro@example.com']);

        // 5回までは通常どおりエラーを返す
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'taro@example.com', 'password' => 'wrongpass'])
                ->assertStatus(302);
        }

        // 6回目は throttle が 429 を返す
        $this->post('/login', ['email' => 'taro@example.com', 'password' => 'wrongpass'])
            ->assertStatus(429);
    }
}
