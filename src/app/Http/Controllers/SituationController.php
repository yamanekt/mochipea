<?php

namespace App\Http\Controllers;

use App\Models\Goal;

class SituationController extends Controller
{
    public function show($id)
    {
        $goal = Goal::findOrFail($id);

        return view('situation', compact('goal'));
    }
}
