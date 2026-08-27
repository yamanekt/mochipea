<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SituationController extends Controller
{
    public function show($id)
    {
        $goal = Goal::findOrFail($id);

        $userId = Auth::id();

        // 自分
        $myValue = GoalProgress::where('goal_id', $id)
            ->where('user_id', $userId)
            ->sum('value');

        $myRate = Goal::progressRate($myValue, $goal->target_value);

        // 入力した達成日の順に並べる（記録した日時ではない）
        $myHistory = GoalProgress::where('goal_id', $id)
            ->where('user_id', $userId)
            ->orderByDesc('progress_date')
            ->orderByDesc('id')
            ->get();

        // ペア相手
        $partnerId = DB::table('pairs')
            ->join('goals', 'pairs.id', '=', 'goals.pair_id')
            ->where('goals.id', $id)
            ->select(DB::raw("
                CASE
                    WHEN user1_id = {$userId} THEN user2_id
                    ELSE user1_id
                END AS partner_id
            "))
            ->value('partner_id');

        $partnerValue = GoalProgress::where('goal_id', $id)
            ->where('user_id', $partnerId)
            ->sum('value');

        $partnerRate = Goal::progressRate($partnerValue, $goal->target_value);

        $partnerHistory = GoalProgress::where('goal_id', $id)
            ->where('user_id', $partnerId)
            ->orderByDesc('progress_date')
            ->orderByDesc('id')
            ->get();

        return view('situation', compact(
            'goal',
            'myValue',
            'myRate',
            'myHistory',
            'partnerValue',
            'partnerRate',
            'partnerHistory'
        ));
    }
}
