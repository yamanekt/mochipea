<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function create()
    {
        $roomId = strtoupper(Str::random(6));

        Room::create([
            'room_id' => $roomId
        ]);

        return redirect('/room/' . $roomId);
    }

    public function show($roomId)
    {
        $room = Room::where(
            'room_id',
            $roomId
        )->firstOrFail();

        return view('room', compact('room'));
    }
}
