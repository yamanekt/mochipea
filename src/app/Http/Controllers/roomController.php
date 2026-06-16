<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Room;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function home()
    {
        return view('home');
    }

    //4桁のパスワード
    public function create(Request $request)
{
    $roomId = strtoupper(Str::random(6));

    Room::create([
        'room_id' => $roomId,
        'password' => $request->roomNumber
    ]);

    return redirect('/room/' . $roomId);
}

    public function show($roomId)
    {
        $room = Room::where(
            'room_id',
            $roomId
        )->firstOrFail();

         return view('goals');
    }
}
