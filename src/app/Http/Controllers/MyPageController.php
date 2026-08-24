<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Pair;

class MyPageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 目標はペア経由でぶら下がるため、自分が属するペアを先に集める
        $pairIds = Pair::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->pluck('id');

        $goalCount = Goal::whereIn('pair_id', $pairIds)->count();

        // 勝率(ベタ書き)
        $winRate = 67;

        return view('mypage', compact('user', 'goalCount', 'winRate'));
    }
}
