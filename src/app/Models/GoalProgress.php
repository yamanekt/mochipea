<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalProgress extends Model
{
    protected $fillable = [
    'goal_id',
    'user_id',
    'value',
    'memo',
    'progress_date',
];
}
