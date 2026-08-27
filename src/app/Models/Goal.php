<?php

// Goal モデル — goalsテーブルに対応
// ペアが設定した「目標」を管理する（例: 「腕立て100回」「毎日30分勉強」）

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
   protected $fillable = [
    'pair_id',
    'category',
    'mode',
    'title',
    'target_value',
    'unit',
    'deadline',
];

    protected $casts = [
        'deadline' => 'date',
        'finished_at' => 'datetime',
    ];

    /** 0から目標値まで積み上げる（従来の方式） */
    public const MODE_ACCUMULATE = 'accumulate';

    /** 目標値を相手のHPとみなし、自分の記録で削っていく */
    public const MODE_SURVIVAL = 'survival';

    public const MODES = [
        self::MODE_ACCUMULATE => '積み上げ',
        self::MODE_SURVIVAL   => 'サバイバル',
    ];

    public const MODE_DESCRIPTIONS = [
        self::MODE_ACCUMULATE => '目標値まで記録を積み上げます',
        self::MODE_SURVIVAL   => '記録した分だけ相手のHPを削ります',
    ];

    public function isSurvival(): bool
    {
        return $this->mode === self::MODE_SURVIVAL;
    }

    /**
     * 達成率（％）を求める。
     *
     * round() を使うと 4999/5000 が 99.98 → 100 に切り上がり、
     * 未達成なのに「達成」と表示されてしまうため floor() で切り捨てる。
     * 100 を返すのは実際に目標値に到達したときだけ。
     */
    public static function progressRate(int|float $current, int|float $target): int
    {
        if ($target <= 0) {
            return 0;
        }

        return (int) floor(min($current, $target) / $target * 100);
    }

    /**
     * 目標値を超えて積み上げた分（超過分）。達成後も記録を続けられるようにする。
     */
    public static function overflowValue(int|float $current, int|float $target): int
    {
        return (int) max(0, $current - $target);
    }

    /**
     * サバイバルモードでの残HP。
     * 自分の記録（$attack）が相手のHPを削るので、残りは 目標値 − 相手の攻撃量。
     */
    public static function remainingHp(int|float $incomingDamage, int|float $maxHp): int
    {
        return (int) max(0, $maxHp - $incomingDamage);
    }

    /** HPが0になったら決着 */
    public static function isDefeated(int|float $incomingDamage, int|float $maxHp): bool
    {
        return $maxHp > 0 && $incomingDamage >= $maxHp;
    }

    public const RESULT_WIN  = 'win';
    public const RESULT_LOSE = 'lose';
    public const RESULT_DRAW = 'draw';

    public const RESULT_LABELS = [
        self::RESULT_WIN  => '勝ち',
        self::RESULT_LOSE => '負け',
        self::RESULT_DRAW => '引き分け',
    ];

    /**
     * 勝敗を決める。多く記録した方の勝ち、同数なら引き分け。
     * モードによらず「合計値の大小」で判定できる
     * （サバイバルも、より多く削った側が勝ちなので同じ比較でよい）。
     */
    public static function judge(int|float $myValue, int|float $partnerValue): string
    {
        if ($myValue > $partnerValue) {
            return self::RESULT_WIN;
        }

        if ($myValue < $partnerValue) {
            return self::RESULT_LOSE;
        }

        return self::RESULT_DRAW;
    }

    /** 相手から見た結果に反転する（勝ち↔負け、引き分けはそのまま） */
    public static function invertResult(string $result): string
    {
        return match ($result) {
            self::RESULT_WIN  => self::RESULT_LOSE,
            self::RESULT_LOSE => self::RESULT_WIN,
            default           => self::RESULT_DRAW,
        };
    }

    /** 期限を過ぎているか */
    public function isExpired(): bool
    {
        return $this->deadline !== null && $this->deadline->isBefore(now()->startOfDay());
    }
}
