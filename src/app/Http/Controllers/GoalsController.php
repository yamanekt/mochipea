<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Pair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoalsController extends Controller
{
    /**
     * Create a waiting room with the goal set by the room owner.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'target_value' => ['required', 'integer', 'min:1'],
            'unit' => ['required', 'string', 'max:50'],
            'deadline' => ['required', 'date'],
        ]);

        $ownerId = auth()->id();

        $room = DB::transaction(function () use ($request, $ownerId) {
            $roomId = $this->generateRoomCode();

            DB::table('rooms')->insert([
                'room_id' => $roomId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pair = Pair::create([
                'user1_id' => $ownerId,
                'user2_id' => $ownerId,
                'pair_code' => $roomId,
            ]);

            DB::table('pair_codes')->insert([
                'user_id' => $ownerId,
                'code' => $roomId,
                'status' => 'waiting',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Goal::create([
                'pair_id' => $pair->id,
                'category' => $request->category,
                'title' => $request->title,
                'target_value' => $request->target_value,
                'unit' => $request->unit,
                'deadline' => $request->deadline,
            ]);

            return (object) ['room_id' => $roomId];
        });

        return view('make', compact('room'));
    }

    private function generateRoomCode(): string
    {
        do {
            $roomId = (string) random_int(1000, 9999);
        } while (
            DB::table('rooms')->where('room_id', $roomId)->exists()
            || DB::table('pair_codes')
                ->where('code', $roomId)
                ->where('status', 'waiting')
                ->exists()
        );

        return $roomId;
    }
}
