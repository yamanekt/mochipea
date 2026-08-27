<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PairRoomController extends Controller
{
    /**
     * ペア設定のメニュー。待機中の件数だけ添える。
     */
    public function index()
    {
        $waitingCount = DB::table('pair_codes')
            ->where('user_id', auth()->id())
            ->where('status', 'waiting')
            ->count();

        return view('pea', compact('waitingCount'));
    }

    /**
     * 発行済みで、まだ相手が参加していない部屋の一覧。
     * 部屋番号は /make を離れると確認できなくなるため、ここから見返せるようにする。
     */
    public function waiting()
    {
        $rooms = DB::table('pair_codes')
            ->join('pairs', 'pairs.pair_code', '=', 'pair_codes.code')
            ->join('goals', 'goals.pair_id', '=', 'pairs.id')
            ->where('pair_codes.user_id', auth()->id())
            ->where('pair_codes.status', 'waiting')
            ->orderByDesc('pair_codes.created_at')
            ->select([
                'pair_codes.code',
                'pair_codes.created_at',
                'goals.title',
            ])
            ->get();

        return view('pair-waiting', compact('rooms'));
    }

    /**
     * まだ相手が参加していない部屋を取り消す。
     *
     * rooms / pair_codes / pairs / goals の4テーブルにまたがるので
     * トランザクションで囲み、途中で失敗したら1件も消えないようにする。
     */
    public function cancel(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:4']]);

        $code = $request->input('code');
        $userId = auth()->id();

        $deleted = DB::transaction(function () use ($code, $userId) {
            // 自分が発行した待機中の部屋か確認する（他人の部屋を消せないように）
            $pairCode = DB::table('pair_codes')
                ->where('code', $code)
                ->where('user_id', $userId)
                ->where('status', 'waiting')
                ->lockForUpdate()
                ->first();

            if (! $pairCode) {
                return false;
            }

            // ペアが成立していない（自分が両方に入っている）ものだけ削除対象
            $pair = DB::table('pairs')
                ->where('pair_code', $code)
                ->where('user1_id', $userId)
                ->whereColumn('user1_id', 'user2_id')
                ->first();

            if (! $pair) {
                return false;
            }

            // goals は pair_id に cascadeOnDelete が付いているので pairs を消せば一緒に消える
            DB::table('pairs')->where('id', $pair->id)->delete();
            DB::table('pair_codes')->where('id', $pairCode->id)->delete();
            DB::table('rooms')->where('room_id', $code)->delete();

            return true;
        });

        return $deleted
            ? redirect()->route('pair.waiting')->with('success', '部屋を取り消しました。')
            : redirect()->route('pair.waiting')->withErrors(['cancel' => 'この部屋は取り消せませんでした。すでに相手が参加している可能性があります。']);
    }
}
