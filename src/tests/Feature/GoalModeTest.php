<?php

namespace Tests\Feature;

use App\Models\Goal;
use Tests\TestCase;

class GoalModeTest extends TestCase
{
    public function test_サバイバルは受けたダメージ分HPが減る(): void
    {
        // 相手が300削ったら、HP1000のうち残り700
        $this->assertSame(700, Goal::remainingHp(300, 1000));
        $this->assertSame(1000, Goal::remainingHp(0, 1000));
    }

    public function test_HPはマイナスにならない(): void
    {
        $this->assertSame(0, Goal::remainingHp(1500, 1000));
    }

    public function test_HPが0になったら決着(): void
    {
        $this->assertTrue(Goal::isDefeated(1000, 1000));
        $this->assertTrue(Goal::isDefeated(1200, 1000));
        $this->assertFalse(Goal::isDefeated(999, 1000));
    }

    public function test_勝敗は記録量の大小で決まる(): void
    {
        $this->assertSame(Goal::RESULT_WIN,  Goal::judge(500, 400));
        $this->assertSame(Goal::RESULT_LOSE, Goal::judge(400, 500));
        $this->assertSame(Goal::RESULT_DRAW, Goal::judge(500, 500));
    }

    public function test_相手から見た結果は反転する(): void
    {
        $this->assertSame(Goal::RESULT_LOSE, Goal::invertResult(Goal::RESULT_WIN));
        $this->assertSame(Goal::RESULT_WIN,  Goal::invertResult(Goal::RESULT_LOSE));
        $this->assertSame(Goal::RESULT_DRAW, Goal::invertResult(Goal::RESULT_DRAW));
    }

    public function test_モードの判定(): void
    {
        $accumulate = new Goal(['mode' => Goal::MODE_ACCUMULATE]);
        $survival   = new Goal(['mode' => Goal::MODE_SURVIVAL]);

        $this->assertFalse($accumulate->isSurvival());
        $this->assertTrue($survival->isSurvival());
    }

    public function test_モードを指定しなければ積み上げになる(): void
    {
        $this->assertArrayHasKey(Goal::MODE_ACCUMULATE, Goal::MODES);
        $this->assertArrayHasKey(Goal::MODE_SURVIVAL, Goal::MODES);
        $this->assertCount(2, Goal::MODES);
    }
}
