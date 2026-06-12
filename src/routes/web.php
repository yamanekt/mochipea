<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\MenuController;
use App\Http\Controllers\RoomController;


Route::get('/', function () {
    return view('home');
});

Route::get('/room/{roomId}', function ($roomId) {
    return view('room', [
        'roomId' => $roomId
    ]);
});


Route::get('/', [RoomController::class, 'home']);

Route::post('/create-room', [RoomController::class, 'create']);

Route::get('/room/{roomId}', [RoomController::class, 'show']);

Route::get('/login', fn() => view('login'));
Route::get('/register', fn() => view('register'));
Route::get('/home', fn() => view('home'));
Route::get('/pea', fn() => view('pea'));
Route::get('/make', fn() => view('make'));
Route::get('/join', fn() => view('join'));
Route::get('/goals', fn() => view('goals'));
Route::get('/current-goals', fn() => view('current-goals'));
Route::get('/situation', fn() => view('situation'));
Route::get('/progress', fn() => view('progress'));
