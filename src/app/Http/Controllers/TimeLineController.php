<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimeLineController extends Controller
{
    /**
     * タイムライン画面
     */
    public function index()
    {
        // ログイン中ユーザーのID
        $userId = Auth::id();

        $entries = DB::table('goal_progress')
            // どの目標への入力か → goals からタイトルを取る
            ->join('goals', 'goal_progress.goal_id', '=', 'goals.id')
            // その目標のペア情報 → 自分が参加しているか絞り込むのに使う
            ->join('pairs', 'goals.pair_id', '=', 'pairs.id')
            // 入力した本人 → users から名前を取る
            ->join('users', 'goal_progress.user_id', '=', 'users.id')
            // 自分がuser1 or user2として参加しているペアの記録だけに限定
            ->where(function ($query) use ($userId) {
                $query->where('pairs.user1_id', $userId)
                    ->orWhere('pairs.user2_id', $userId);
            })
            ->select(
                'goal_progress.goal_id',        // 詳細画面へのリンク用
                'goals.title',                  // 目標名
                'users.name as user_name',      // 入力した人の名前
                'goal_progress.memo',           // コメント（未入力ならNULL）
                'goal_progress.created_at'      // 入力日時（「◯日前」の計算に使う）
            )
            // 新しい入力を上に
            ->orderByDesc('goal_progress.created_at')
            ->get();

        return view('timeline', compact('entries'));
    }
}
