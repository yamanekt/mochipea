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

        $myRate = $goal->target_value > 0
            ? round($myValue / $goal->target_value * 100, 2)
            : 0;

        $myHistory = GoalProgress::where('goal_id', $id)
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
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

        $partnerRate = $goal->target_value > 0
            ? round($partnerValue / $goal->target_value * 100, 2)
            : 0;

        $partnerHistory = GoalProgress::where('goal_id', $id)
            ->where('user_id', $partnerId)
            ->orderByDesc('created_at')
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
