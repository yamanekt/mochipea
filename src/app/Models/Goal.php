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
    'title',
    'target_value',
    'unit',
    'deadline',
];

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
}
