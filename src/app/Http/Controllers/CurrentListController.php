<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurrentListController extends Controller
{
    /**
     * Show only goals that belong to pairs joined by the current user.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $userId = Auth::id();
        $sort = $request->get('sort', 'updated');

        $progressSubQuery = DB::table('goal_progress')
            ->select(
                'goal_id',
                'user_id',
                DB::raw('SUM(value) AS current_value')
            )
            ->groupBy('goal_id', 'user_id');

        $goals = DB::table('goals')
            ->join('pairs', 'goals.pair_id', '=', 'pairs.id')
            ->join('users as partner', function ($join) use ($userId) {
                $join->on('partner.id', '=', DB::raw(
                    "CASE
                        WHEN pairs.user1_id = {$userId}
                        THEN pairs.user2_id
                        ELSE pairs.user1_id
                    END"
                ));
            })
            ->leftJoinSub($progressSubQuery, 'progress', function ($join) use ($userId) {
                $join->on('progress.goal_id', '=', 'goals.id')
                    ->where('progress.user_id', '=', $userId);
            })
            ->where(function ($query) use ($userId) {
                $query->where('pairs.user1_id', $userId)
                    ->orWhere('pairs.user2_id', $userId);
            })
            ->whereColumn('pairs.user1_id', '<>', 'pairs.user2_id')
            ->select(
                'goals.id',
                'goals.title',
                'goals.category',
                'goals.target_value',
                'goals.unit',
                'goals.deadline',
                'goals.status',
                'partner.name as partner_name',
                DB::raw('COALESCE(progress.current_value, 0) AS current_value')
            );
  if ($sort === 'created') {
    $goals->orderBy('goals.created_at', 'desc');
} else {
    $goals->leftJoin(
        DB::raw('(SELECT goal_id, MAX(updated_at) AS last_update
                  FROM goal_progress
                  GROUP BY goal_id) gp'),
        'gp.goal_id',
        '=',
        'goals.id'
    )
    ->orderByDesc('gp.last_update')
    ->orderByDesc('goals.created_at');
}

$goals = $goals->get();

        $goals->transform(function ($goal) {
            $goal->progress_rate = $goal->target_value > 0
                ? floor(($goal->current_value / $goal->target_value) * 1000) / 10
                : 0;

            if (Carbon::parse($goal->deadline)->isBefore(Carbon::today())) {
                $goal->display_status = '期限切れ';
            } elseif ($goal->current_value >= $goal->target_value) {
                $goal->display_status = '目標達成中';
            } else {
                $goal->display_status = '進行中';
            }

            return $goal;
        });

        return view('current-goals', compact('goals', 'sort'));
    }
}
