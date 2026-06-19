<?php

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
}
