<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CorrentListController extends Controller
{
    public function index()
    {
        // ログインしているユーザーのIDを取得
        $userId = Auth::id();

        // ログインしていない場合はログイン画面へ戻す
        if (!$userId) {
            return redirect('/login');
        }

        /*
         * goal_progressテーブルから、
         * 目標ごとの進捗valueの合計を取得する
         */
        $progressSubQuery = DB::table('goal_progress')
            ->select(
                'goal_id',
                'user_id',
                DB::raw('SUM(value) AS current_value')
            )
            ->groupBy('goal_id', 'user_id');

        /*
         * ログイン中ユーザーが参加しているペアの目標を取得
         */
        $goals = DB::table('goals')
            ->join('pairs', 'goals.pair_id', '=', 'pairs.id')

            // ペア相手の情報を取得
            ->join('users as partner', function ($join) use ($userId) {
                $join->on('partner.id', '=', DB::raw(
                    "CASE
                        WHEN pairs.user1_id = {$userId}
                        THEN pairs.user2_id
                        ELSE pairs.user1_id
                    END"
                ));
            })

            // ログイン中ユーザーの進捗を結合
            ->leftJoinSub(
                $progressSubQuery,
                'progress',
                function ($join) use ($userId) {
                    $join->on('progress.goal_id', '=', 'goals.id')
                        ->where('progress.user_id', '=', $userId);
                }
            )

            // 自分が所属しているペアだけ取得
            ->where(function ($query) use ($userId) {
                $query->where('pairs.user1_id', $userId)
                    ->orWhere('pairs.user2_id', $userId);
            })

            ->select(
                'goals.id',
                'goals.title',
                'goals.category',
                'goals.target_value',
                'goals.unit',
                'goals.end_date',
                'partner.name as partner_name',
                DB::raw(
                    'COALESCE(progress.current_value, 0) AS current_value'
                )
            )

            ->orderBy('goals.end_date', 'asc')
            ->get();

        /*
         * 取得した目標に進捗率と状態を追加
         */
        $goals->transform(function ($goal) {
            // 進捗率を計算
            if ($goal->target_value > 0) {
                $goal->progress_rate = round(
                    ($goal->current_value / $goal->target_value) * 100
                );

                // 100%を超えた場合は100%にする
                $goal->progress_rate = min(
                    $goal->progress_rate,
                    100
                );
            } else {
                $goal->progress_rate = 0;
            }

            // 状態を判定
            if ($goal->progress_rate >= 100) {
                $goal->status = '達成済み';
            } else {
                $goal->status = '進行中';
            }

            return $goal;
        });

        // current-goals.blade.phpへデータを渡す
        return view('current-goals', compact('goals'));
    }
}
