<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\GoalProgress;

class GoalProgressController extends Controller
{
    // 達成入力画面
    public function show($id)
    {
        $goal = Goal::findOrFail($id);

        $current = GoalProgress::where('goal_id', $goal->id)
            ->sum('value');

        return view('progress', compact('goal', 'current'));
    }

    // 達成記録保存
    public function store(Request $request)
    {
        GoalProgress::create([
            'goal_id' => $request->goal_id,
            'user_id' => auth()->id(),
            'value' => $request->value,
            'memo' => $request->memo,
            'progress_date' => $request->progress_date,
        ]);

        return redirect('/situation');
    }
}
