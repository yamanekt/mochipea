<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;

class GoalsController extends Controller
{
    public function store(Request $request)
    {
        Goal::create([
            'category' => $request->category,
            'title' => $request->title,
            'target_value' => $request->target_value,
            'unit' => $request->unit,
            'deadline' => $request->deadline,
        ]);

        return redirect('/');
    }
}
