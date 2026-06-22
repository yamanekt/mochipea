<?php

// Pair モデル — pairsテーブルに対応
// 2人のユーザーを「ペア」として結びつける

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    protected $fillable = [
        'user1_id',
        'user2_id',
        'pair_code',
    ];
}
