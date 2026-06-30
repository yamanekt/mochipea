<?php

// 目標の保存

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    // 4桁数字の被っていない部屋番号を作成
    do {
        $roomId = random_int(1000, 9999);
    } while (Room::where('room_id', $roomId)->exists());

    // Room作成
    $room = Room::create([
        'room_id' => $roomId,
        'password' => null
    ]);

    DB::table('pair_codes')->insert([
        'user_id' => auth()->id(),
        'code' => $roomId,
        'status' => 'waiting',
        'created_at' => now(),
        'updated_at' => now(),
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
