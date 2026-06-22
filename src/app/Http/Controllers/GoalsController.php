<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Pair;

class GoalsController extends Controller
{
    public function store(Request $request)
    {
        // pairs作成
        $pair = Pair::create([
            'user1_id' => 1,
            'user2_id' => 1,
            'pair_code' => '1234',
        ]);

        // goals作成
        Goal::create([
            'pair_id' => $pair->id,
            'category' => $request->category,
            'title' => $request->title,
            'target_value' => $request->target_value,
            'unit' => $request->unit,
            'deadline' => $request->deadline,
        ]);

        return redirect('/make');
    }
}
