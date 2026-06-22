<?php

namespace App\Http\Controllers;

use App\Models\Goal;

class CurrentGoalsController extends Controller
{
    public function index()
    {
        $goals = Goal::all();

        foreach ($goals as $goal) {

            $goal->partner_name = 'テストユーザー';

            $goal->current_value = 0;

            $goal->progress_rate =
                $goal->target_value > 0
                ? round(($goal->current_value / $goal->target_value) * 100)
                : 0;

            $goal->display_status =
                $goal->status === 'active'
                ? '進行中'
                : '達成';
        }

        return view('current-goals', compact('goals'));
    }
}
