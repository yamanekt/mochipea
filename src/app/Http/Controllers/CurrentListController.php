<?php

// 目標一覧（進捗付き）を表示
// goals + pairs + goal_progress を結合して一覧取得する

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;  // ログイン中のユーザー情報
use Illuminate\Support\Facades\DB;    // DBファサード（テーブルを直接操作）
use Carbon\Carbon;                     // 日付操作ライブラリ（Laravelに標準付属）

class CurrentListController extends Controller
{
    /**
     * 目標一覧画面を表示する
     * Route: GET /current-goals
     */
    public function index()
    {
        // ログインユーザーのIDを取得
        $userId = Auth::id();

        // 未ログインならログイン画面へ（通常はmiddlewareで弾かれるので念のため）
        if (!$userId) {
            return redirect('/login');
        }

        // サブクエリ: 進捗の合計値を計算
        // goal_progressテーブルから、目標ごと・ユーザーごとの進捗値（value）の合計を求める
        // 例: goal_id=1, user_id=3 の valueが [10, 20, 5] → SUM = 35
        $progressSubQuery = DB::table('goal_progress')
            ->select(
                'goal_id',
                'user_id',
                DB::raw('SUM(value) AS current_value')  // DB::raw() → SQLをそのまま書く
            )
            ->groupBy('goal_id', 'user_id');  // グループ化（目標×ユーザーごとに集計）

        // メインクエリ: 目標一覧を取得
        $goals = DB::table('goals')

            // goals.pair_id = pairs.id でgoalsテーブルとpairsテーブルを結合
            ->join('pairs', 'goals.pair_id', '=', 'pairs.id')

            // ペア相手の名前を取得するためにusersテーブルを結合
            // CASE文: 自分がuser1ならuser2を、自分がuser2ならuser1を「相手」とする
            ->join('users as partner', function ($join) use ($userId) {
                $join->on('partner.id', '=', DB::raw(
                    "CASE
                        WHEN pairs.user1_id = {$userId}
                        THEN pairs.user2_id
                        ELSE pairs.user1_id
                    END"
                ));
            })

            // サブクエリを結合（自分の進捗だけ取得）
            // leftJoinSub → サブクエリの結果をテーブルのように扱ってLEFT JOINする
            // LEFT JOIN = 進捗データがなくても目標は表示する（NULLになる）
            ->leftJoinSub(
                $progressSubQuery,
                'progress',     // サブクエリに「progress」という別名をつける
                function ($join) use ($userId) {
                    $join->on('progress.goal_id', '=', 'goals.id')
                        ->where('progress.user_id', '=', $userId);  // 自分の進捗だけ
                }
            )

            // 自分が所属しているペアの目標だけに絞る
            ->where(function ($query) use ($userId) {
                $query->where('pairs.user1_id', $userId)
                    ->orWhere('pairs.user2_id', $userId);
            })

            // 取得するカラムを指定
            ->select(
                'goals.id',
                'goals.title',
                'goals.category',
                'goals.target_value',
                'goals.unit',
                'goals.deadline',
                'goals.status',
                'partner.name as partner_name',                          // ペア相手の名前
                DB::raw('COALESCE(progress.current_value, 0) AS current_value')  // COALESCE: NULLなら0にする
            )

            ->orderBy('goals.deadline', 'asc')  // 期限が近い順に並べる
            ->get();                              // クエリを実行して結果を取得

        // 取得した目標に「進捗率」と「表示用ステータス」を追加
        // transform() → コレクション（配列のようなもの）の各要素を変換する
        $goals->transform(function ($goal) {

            // 進捗率を計算（例: 35/100 = 35%）
            if ($goal->target_value > 0) {
                $goal->progress_rate = round(
                    ($goal->current_value / $goal->target_value) * 100
                );
            } else {
                $goal->progress_rate = 0;  // 目標値が0なら進捗率も0
            }

            // 表示用の状態を判定
            if (Carbon::parse($goal->deadline)->isBefore(Carbon::today())) {
                $goal->display_status = '終了';       // 期限切れ
            } elseif ($goal->progress_rate >= 100) {
                $goal->display_status = '目標達成中';  // 100%以上
            } else {
                $goal->display_status = '進行中';      // まだ途中
            }
            return $goal;
        });

        // Bladeテンプレートにデータを渡して表示
        // compact('goals') → ['goals' => $goals] と同じ意味
        return view('current-goals', compact('goals'));
    }
}
