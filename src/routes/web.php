<?php

use App\Http\Controllers\AccountLoginController;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\MenuController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\AccountRegisterController;
use App\Http\Controllers\PairCodeCheckController;
use App\Http\Controllers\CurrentGoalsController;


Route::get('/', function () {
    return view('home');
});

Route::get('/room/{roomId}', function ($roomId) {
    return view('room', [
        'roomId' => $roomId
    ]);
});

Route::post('/goals/store', [GoalsController::class, 'store']);
Route::get('/pair-code-check', [PairCodeCheckController::class, 'show'])
    ->name('pair.code.check');

Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])
    ->name('pair.code.check.post');


Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login']);
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountRegisterController::class, 'register']);
Route::post('/create-room', [RoomController::class, 'create']);


Route::get('/', [RoomController::class, 'home']);

Route::get('/room/{roomId}', function ($roomId) {
    return view('room', ['roomId' => $roomId]);
});

// 認証
Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login']);
Route::post('/logout', [AccountLoginController::class, 'logout'])->name('logout');
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountRegisterController::class, 'register']);

// その他
Route::get('/current-goals', [CorrentListController::class, 'index'])->name('current-goals');
Route::get('/pair-code-check', [PairCodeCheckController::class, 'show'])->name('pair.code.check');
Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])->name('pair.code.check.post');

// 画面表示
Route::get('/', fn() => view('home'));
Route::get('/home', fn() => view('home'));
Route::get('/pea', fn() => view('pea'));
Route::get('/make', fn() => view('make'));
Route::get('/join', fn() => view('join'));
Route::get('/goals', fn() => view('goals'));
Route::get('/situation', fn() => view('situation'));
Route::get('/progress', fn() => view('progress'));
