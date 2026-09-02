<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\GoalProgress;

class GoalProgressController extends Controller
{
    public function show($id)
    {
        $userId = auth()->id();

        // 自分が属するペアの目標だけ開ける
        $goal = Goal::visibleTo($userId)->findOrFail($id);

        // ここは「自分があとどれくらいか」を出す欄なので、相手の記録を混ぜない
        $current = GoalProgress::where('goal_id', $id)
            ->where('user_id', $userId)
            ->sum('value');

        return view('progress', compact('goal', 'current'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'goal_id' => ['required', 'integer', 'exists:goals,id'],
            'value' => ['required', 'integer', 'min:1'],
            'memo' => ['nullable', 'string', 'max:1000'],
            // 未来の日付で記録されると達成率の意味が崩れるため今日までに限る
            'progress_date' => ['required', 'date', 'before_or_equal:today'],
        ], [
            'progress_date.before_or_equal' => '達成日は今日までの日付にしてください。',
        ]);

        $userId = auth()->id();

        // exists だけでは他人の目標に書き込めてしまうので、ここでも絞り込む
        $goal = Goal::visibleTo($userId)->findOrFail($validated['goal_id']);

        GoalProgress::create([
            'goal_id' => $goal->id,
            'user_id' => $userId,
            'value' => $validated['value'],
            'memo' => $validated['memo'] ?? null,
            'progress_date' => $validated['progress_date'],
        ]);

        return redirect()->route('situation.show', $goal->id);
    }
}
