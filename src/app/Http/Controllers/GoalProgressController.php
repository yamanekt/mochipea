<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\GoalProgress;

class GoalProgressController extends Controller
{
    public function show($id)
    {
        $goal = Goal::findOrFail($id);

        $current = GoalProgress::where('goal_id', $id)
            ->sum('value');

        return view('progress', compact('goal', 'current'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'goal_id' => ['required', 'integer', 'exists:goals,id'],
            'value' => ['required', 'integer', 'min:1'],
            'memo' => ['nullable', 'string', 'max:1000'],
            'progress_date' => ['required', 'date'],
        ]);

        GoalProgress::create([
            'goal_id' => $validated['goal_id'],
            'user_id' => auth()->id(),
            'value' => $validated['value'],
            'memo' => $validated['memo'] ?? null,
            'progress_date' => $validated['progress_date'],
        ]);

        return redirect()->route('situation.show', $validated['goal_id']);
    }
}
