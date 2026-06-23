<?php

// Goal モデル — goalsテーブルに対応
// ペアが設定した「目標」を管理する（例: 「腕立て100回」「毎日30分勉強」）

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    /**
     * $fillable — create() で一括登録を許可するカラム
     */
    protected $fillable = [
        'pair_id',
        'category',
        'title',
        'target_value',
        'unit',
        'deadline',
    ];
}
