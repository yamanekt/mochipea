<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\MenuController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AccountLoginController;
use App\Http\Controllers\AccountRegisterController;
use App\Http\Controllers\PairCodeCheckController;



Route::get('/', function () {
    return view('home');
});

Route::get('/room/{roomId}', function ($roomId) {
    return view('room', [
        'roomId' => $roomId
    ]);
});

Route::get('/pair-code-check', [PairCodeCheckController::class, 'show'])
    ->name('pair.code.check');

Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])
    ->name('pair.code.check.post');


Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login']);
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountRegisterController::class, 'register']);



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
