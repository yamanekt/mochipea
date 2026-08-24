<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PairRoomController extends Controller
{
    public function index()
    {
        $waitingCount = DB::table('pair_codes')->where('user_id', auth()->id())->where('status', 'waiting')->count();
        return view('pea', compact('waitingCount'));
    }

    public function waiting()
    {
        $rooms = DB::table('pair_codes')->join('pairs', 'pairs.pair_code', '=', 'pair_codes.code')->join('goals', 'goals.pair_id', '=', 'pairs.id')->where('pair_codes.user_id', auth()->id())->where('pair_codes.status', 'waiting')->orderByDesc('pair_codes.created_at')->select(['pair_codes.code', 'pair_codes.created_at', 'goals.title'])->get();
        return view('pair-waiting', compact('rooms'));
    }
}

