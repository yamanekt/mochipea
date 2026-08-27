<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoalResultController extends Controller
{
    /**
     * 決着済みの目標一覧（対戦履歴）。
     *
     * 勝敗は goals.result に「作成者(user1)から見た結果」で入っているので、
     * 相手として参加していた場合は反転して表示する。
     */
    public function index()
    {
        $userId = Auth::id();

        $goals = DB::table('goals')
            ->join('pairs', 'goals.pair_id', '=', 'pairs.id')
            ->join('users as partner', 'partner.id', '=', DB::raw(
                "CASE WHEN pairs.user1_id = {$userId} THEN pairs.user2_id ELSE pairs.user1_id END"
            ))
            ->where(function ($q) use ($userId) {
                $q->where('pairs.user1_id', $userId)->orWhere('pairs.user2_id', $userId);
            })
            // ペアが成立していない（自分が両方に入っている）ものは対戦ではないので除く
            ->whereColumn('pairs.user1_id', '<>', 'pairs.user2_id')
            ->whereNotNull('goals.result')
            ->orderByDesc('goals.finished_at')
            ->select([
                'goals.id',
                'goals.title',
                'goals.category',
                'goals.mode',
                'goals.unit',
                'goals.target_value',
                'goals.result',
                'goals.finished_at',
                'partner.name as partner_name',
                DB::raw("(pairs.user1_id = {$userId}) as is_owner"),
            ])
            ->get();

        $goals->transform(function ($goal) use ($userId) {
            // 自分から見た勝敗に直す
            $goal->my_result = $goal->is_owner
                ? $goal->result
                : Goal::invertResult($goal->result);

            $goal->my_value = DB::table('goal_progress')
                ->where('goal_id', $goal->id)->where('user_id', $userId)->sum('value');

            $goal->partner_value = DB::table('goal_progress')
                ->where('goal_id', $goal->id)->where('user_id', '<>', $userId)->sum('value');

            return $goal;
        });

        $summary = [
            Goal::RESULT_WIN  => $goals->where('my_result', Goal::RESULT_WIN)->count(),
            Goal::RESULT_LOSE => $goals->where('my_result', Goal::RESULT_LOSE)->count(),
            Goal::RESULT_DRAW => $goals->where('my_result', Goal::RESULT_DRAW)->count(),
        ];

        return view('goal-results', compact('goals', 'summary'));
    }
}
