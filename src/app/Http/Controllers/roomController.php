<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pair;
use App\Models\Room;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function create(Request $request)
    {
        $roomId = strtoupper(Str::random(4));

        Room::create([
            'room_id' => $roomId,
            'password' => $request->roomNumber
        ]);

        Pair::create([
            'user1_id' => auth()->id(),
            'user2_id' => auth()->id(),
            'pair_code' => $roomId
        ]);

        return redirect('/room/' . $roomId);
    }

    public function show($roomId)
    {
        $room = Room::where('room_id', $roomId)->firstOrFail();

        return view('goals', compact('room'));
    }
}
