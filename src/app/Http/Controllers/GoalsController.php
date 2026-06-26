<?php

// 目標の保存
// ⚠️ 現在ダミーデータで動いている（要修正）

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Room;
use App\Models\Pair;

class GoalsController extends Controller
{
    /**
     * 目標をDBに保存する
     * Route: POST /goals/store
     */
    public function store(Request $request)
{
    // 4桁の部屋番号を作成
    $roomId = strtoupper(Str::random(4));

    // Room作成
    $room = Room::create([
        'room_id' => $roomId,
        'password' => null
    ]);

    // Pair作成
    $pair = Pair::create([

        'user1_id' => auth()->id(),
        'user2_id' => auth()->id(),
        'pair_code' => $roomId
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

    // 部屋番号表示画面へ
    return view('make', compact('room'));
}
}
