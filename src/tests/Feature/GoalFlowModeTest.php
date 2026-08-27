<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalFlowModeTest extends TestCase
{
    use RefreshDatabase;

    private function draft(string $mode = Goal::MODE_ACCUMULATE): array
    {
        return [
            'category'     => 'study',
            'mode'         => $mode,
            'title'        => '英単語を覚える',
            'target_value' => 1000,
            'unit'         => '語',
            'deadline'     => now()->addMonth()->toDateString(),
        ];
    }

    public function test_step1でモードを選べる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/goals/new/1')
            ->assertOk()
            ->assertSee('すすめかた')
            ->assertSee('積み上げ')
            ->assertSee('サバイバル');
    }

    public function test_モードなしでは次に進めない(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/goals/new/1')
            ->post('/goals/new/1', ['category' => 'study'])
            ->assertSessionHasErrors('mode');
    }

    public function test_選んだモードが保存される(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/goals/new/1', ['category' => 'study', 'mode' => Goal::MODE_SURVIVAL])
            ->assertRedirect(route('goals.new.step', ['step' => 2]))
            ->assertSessionHas('goal_draft.mode', Goal::MODE_SURVIVAL);
    }

    public function test_サバイバルで作成するとDBに記録される(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['goal_draft' => $this->draft(Goal::MODE_SURVIVAL)])
            ->post('/goals/new')
            ->assertRedirect(route('make'));

        $this->assertDatabaseHas('goals', [
            'title' => '英単語を覚える',
            'mode'  => Goal::MODE_SURVIVAL,
        ]);
    }

    public function test_確認画面にモードが出る(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['goal_draft' => $this->draft(Goal::MODE_SURVIVAL)])
            ->get('/goals/new/5')
            ->assertOk()
            ->assertSee('すすめかた')
            ->assertSee('サバイバル');
    }

    public function test_モードを持たない既存データは積み上げ扱いになる(): void
    {
        // このカラムを追加する前に作られた目標を想定して、mode を指定せず1行入れる
        $pairId = \Illuminate\Support\Facades\DB::table('pairs')->insertGetId([
            'user1_id' => User::factory()->create()->id,
            'user2_id' => User::factory()->create()->id,
            'pair_code' => '1234',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('goals')->insert([
            'pair_id' => $pairId,
            'category' => 'study',
            'title' => '移行前からある目標',
            'target_value' => 100,
            'unit' => '語',
            'deadline' => now()->addWeek()->toDateString(),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $goal = Goal::where('title', '移行前からある目標')->first();

        $this->assertSame(Goal::MODE_ACCUMULATE, $goal->mode);
        $this->assertFalse($goal->isSurvival());
    }

    public function test_モード未選択のドラフトでは決定できない(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['goal_draft' => collect($this->draft())->except('mode')->all()])
            ->post('/goals/new')
            ->assertRedirect(route('goals.new.step', ['step' => 1]));

        $this->assertDatabaseCount('goals', 0);
    }
}
