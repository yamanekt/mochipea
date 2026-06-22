<?php

// 目標の保存
// ⚠️ 現在ダミーデータで動いている（要修正）

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Pair;

class GoalsController extends Controller
{
    /**
     * 目標をDBに保存する
     * Route: POST /goals/store
     */
    public function store(Request $request)
    {
        // ⚠️ TODO: ここはダミー。本来はログインユーザーの既存ペアIDを取得して使う
        $pair = Pair::create([
            'user1_id' => 1,       // ← 本来は Auth::id()
            'user2_id' => 1,       // ← 本来はペア相手のID
            'pair_code' => '1234', // ← 本来はペアコード照合時のコード
        ]);

        // goalsテーブルに目標を保存
        // $request->category などはフォームのname属性と対応している
        Goal::create([
            'pair_id' => $pair->id,             // 上で作ったペアのID
            'category' => $request->category,    // カテゴリ（運動/勉強/ゲームなど）
            'title' => $request->title,          // 目標タイトル
            'target_value' => $request->target_value, // 目標値（数値）
            'unit' => $request->unit,            // 単位（回/分/ページなど）
            'deadline' => $request->deadline,    // 期限
        ]);

        return redirect('/'); // ホームにリダイレクト
    }
}
