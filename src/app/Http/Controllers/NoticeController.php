<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * お知らせ。
 *
 * 専用テーブルは作らず、既存のデータ（相手の記録・決着した目標・成立したペア）から
 * その都度組み立てる。件数が増えても直近だけ見せればよいので、この作りで足りる。
 */
class NoticeController extends Controller
{
    private const LIMIT = 30;

    public function index()
    {
        $userId = Auth::id();

        // 自分が参加していて、ペアが成立している目標
        $goalIds = DB::table('goals')
            ->join('pairs', 'goals.pair_id', '=', 'pairs.id')
            ->where(fn ($q) => $q->where('pairs.user1_id', $userId)->orWhere('pairs.user2_id', $userId))
            ->whereColumn('pairs.user1_id', '<>', 'pairs.user2_id')
            ->pluck('goals.id');

        $notices = collect();

        // 相手が記録したもの
        $progress = DB::table('goal_progress')
            ->join('goals', 'goal_progress.goal_id', '=', 'goals.id')
            ->join('users', 'goal_progress.user_id', '=', 'users.id')
            ->whereIn('goal_progress.goal_id', $goalIds)
            ->where('goal_progress.user_id', '<>', $userId)
            ->orderByDesc('goal_progress.created_at')
            ->limit(self::LIMIT)
            ->select([
                'goal_progress.created_at',
                'goal_progress.value',
                'goal_progress.memo',
                'goals.id as goal_id',
                'goals.title',
                'goals.unit',
                'goals.mode',
                'users.name as user_name',
            ])
            ->get();

        foreach ($progress as $row) {
            $notices->push((object) [
                'type'    => 'progress',
                'at'      => $row->created_at,
                'goal_id' => $row->goal_id,
                'title'   => $row->title,
                'body'    => $row->mode === Goal::MODE_SURVIVAL
                    ? "{$row->user_name}さんが{$row->value}{$row->unit}削りました"
                    : "{$row->user_name}さんが{$row->value}{$row->unit}記録しました",
                'memo'    => $row->memo,
            ]);
        }

        // 決着した目標
        $finished = DB::table('goals')
            ->join('pairs', 'goals.pair_id', '=', 'pairs.id')
            ->whereIn('goals.id', $goalIds)
            ->whereNotNull('goals.result')
            ->orderByDesc('goals.finished_at')
            ->limit(self::LIMIT)
            ->select(['goals.id', 'goals.title', 'goals.result', 'goals.finished_at', 'pairs.user1_id'])
            ->get();

        foreach ($finished as $row) {
            // result は作成者視点で入っているので、相手として参加していたら反転する
            $myResult = ((int) $row->user1_id === $userId)
                ? $row->result
                : Goal::invertResult($row->result);

            $notices->push((object) [
                'type'    => 'result',
                'at'      => $row->finished_at,
                'goal_id' => $row->id,
                'title'   => $row->title,
                'body'    => '決着しました：' . Goal::RESULT_LABELS[$myResult],
                'memo'    => null,
            ]);
        }

        $notices = $notices
            ->filter(fn ($n) => $n->at !== null)
            ->sortByDesc('at')
            ->take(self::LIMIT)
            ->values();

        return view('notices', compact('notices'));
    }
}
