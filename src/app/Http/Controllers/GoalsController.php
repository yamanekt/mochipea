<?php

// 目標の保存

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        // 4桁数字の被っていないコードを作成
        do {
            $code = random_int(1000, 9999);
        } while (DB::table('pair_codes')->where('code', $code)->exists());

        // pair_codesテーブルにコードを保存
        DB::table('pair_codes')->insert([
            'user_id' => auth()->id(),
            'code' => $code,
            'status' => 'waiting',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Pair作成（user2_idは相手が参加するまで自分のIDを仮で入れる）
        $pair = Pair::create([
            'user1_id' => auth()->id(),
            'user2_id' => auth()->id(),
            'pair_code' => $code,
        ]);

        // Goal保存
        Goal::create([
            'pair_id' => $pair->id,
            'category' => $request->category,
            'title' => $request->title,
            'target_value' => $request->target_value,
            'unit' => $request->unit,
            'deadline' => $request->deadline,
        ]);

        // 部屋番号表示画面へ（コードを渡す）
        return view('make', ['code' => $code]);
    }
}
