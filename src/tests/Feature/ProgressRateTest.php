<?php

namespace Tests\Feature;

use App\Models\Goal;
use Tests\TestCase;

class ProgressRateTest extends TestCase
{
    /**
     * 目標5000に対して4999を入力すると、round() では 99.98 → 100 に
     * 切り上がってしまい「達成」と誤表示されていた。
     */
    public function test_未達成のときに100パーセントにならない(): void
    {
        $this->assertSame(99, Goal::progressRate(4999, 5000));
        $this->assertSame(99, Goal::progressRate(999, 1000));
        $this->assertSame(99, Goal::progressRate(99999, 100000));
    }

    public function test_ちょうど到達したときだけ100になる(): void
    {
        $this->assertSame(100, Goal::progressRate(5000, 5000));
        $this->assertSame(100, Goal::progressRate(1, 1));
    }

    public function test_超過しても100で頭打ちになる(): void
    {
        $this->assertSame(100, Goal::progressRate(7000, 5000));
        $this->assertSame(100, Goal::progressRate(1000000, 5000));
    }

    public function test_端数は切り捨てる(): void
    {
        $this->assertSame(0, Goal::progressRate(0, 5000));
        $this->assertSame(50, Goal::progressRate(2500, 5000));
        // 2549/5000 = 50.98% → 50
        $this->assertSame(50, Goal::progressRate(2549, 5000));
    }

    public function test_目標値がゼロでも落ちない(): void
    {
        $this->assertSame(0, Goal::progressRate(10, 0));
        $this->assertSame(0, Goal::progressRate(0, 0));
    }

    public function test_超過分を取り出せる(): void
    {
        $this->assertSame(0, Goal::overflowValue(4999, 5000));
        $this->assertSame(0, Goal::overflowValue(5000, 5000));
        $this->assertSame(2000, Goal::overflowValue(7000, 5000));
    }
}
