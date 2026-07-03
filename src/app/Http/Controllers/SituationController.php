<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Support\Facades\Auth;

class SituationController extends Controller
{
    public function show($id)
    {
        $goal = Goal::findOrFail($id);

        // 自分の達成数
        $myValue = GoalProgress::where('goal_id', $id)
            ->where('user_id', Auth::id())
            ->sum('value');

        // 達成率
        $myRate = $goal->target_value > 0
            ? round($myValue / $goal->target_value * 100)
            : 0;

        return view('situation', compact(
            'goal',
            'myValue',
            'myRate'
        ));
    }
}
