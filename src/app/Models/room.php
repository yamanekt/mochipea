<?php

// Room モデル — roomsテーブルに対応
// ⚠️ 現在未使用。pair_codesテーブルで代替しているので削除してOK

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_id',
        'id'
    ];
}
