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

        // 目標値に到達したあとも記録を続けられるよう、超過分を出す
        $myOverflow = Goal::overflowValue($myValue, $goal->target_value);
        $partnerOverflow = Goal::overflowValue($partnerValue, $goal->target_value);

        // サバイバルは自分の記録が相手のHPを削る（＝相手の残HPは自分の記録で決まる）
        $myRemainingHp = Goal::remainingHp($partnerValue, $goal->target_value);
        $partnerRemainingHp = Goal::remainingHp($myValue, $goal->target_value);

        // 期限が来ていれば勝敗を確定させる
        $result = $this->finalizeIfExpired($goal, $myValue, $partnerValue);

        return view('situation', compact(
            'goal',
            'myValue',
            'myRate',
            'myOverflow',
            'myRemainingHp',
            'myHistory',
            'partnerValue',
            'partnerRate',
            'partnerOverflow',
            'partnerRemainingHp',
            'partnerHistory',
            'result'
        ));
    }

    /**
     * 期限を過ぎた目標を一度だけ終了させ、勝敗を保存する。
     *
     * result は「作成者（user1）から見た結果」で保存し、
     * 表示時に相手側なら反転させる。閲覧者ごとに書き換えないため
     * 誰が見ても同じ結果になる。
     */
    private function finalizeIfExpired(Goal $goal, int|float $myValue, int|float $partnerValue): ?string
    {
        $isOwner = DB::table('pairs')->where('id', $goal->pair_id)->value('user1_id') === Auth::id();

        if ($goal->isExpired() && $goal->result === null) {
            $ownerValue   = $isOwner ? $myValue : $partnerValue;
            $opponentValue = $isOwner ? $partnerValue : $myValue;

            $goal->forceFill([
                'status'      => 'finished',
                'result'      => Goal::judge($ownerValue, $opponentValue),
                'finished_at' => now(),
            ])->save();
        }

        if ($goal->result === null) {
            return null;
        }

        return $isOwner ? $goal->result : Goal::invertResult($goal->result);
    }
}
