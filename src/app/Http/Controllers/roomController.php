<?php

// 部屋の作成・表示（⚠️ 現在未使用。削除してOK）

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Str;  // ランダム文字列を生成するヘルパー

class RoomController extends Controller
{
    /**
     * ホーム画面を表示（未使用）
     */
    public function home()
    {
        return view('home');
    }

    /**
     * 部屋を作成する（未使用）
     * Str::random(6) → ランダムな6文字の英数字を生成
     * strtoupper() → 大文字に変換
     */
    public function create(Request $request)
    {
        $roomId = strtoupper(Str::random(6));

        Room::create([
            'room_id' => $roomId,
            'password' => $request->roomNumber
        ]);

        return redirect('/room/' . $roomId);
    }

    /**
     * 部屋を表示する（未使用）
     * firstOrFail() → 見つからなかったら404エラーを返す
     */
    public function show($roomId)
    {
        $room = Room::where('room_id', $roomId)->firstOrFail();
        return view('goals');
    }
}
