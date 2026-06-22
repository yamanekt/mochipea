<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountLoginController;
use App\Http\Controllers\AccountRegisterController;
use App\Http\Controllers\CurrentListController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\PairCodeCheckController;

// ── 認証不要（ゲスト用） ──
Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login']);
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountRegisterController::class, 'register']);

// ── ログイン必須 ──
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AccountLoginController::class, 'logout'])->name('logout');

    // ホーム
    Route::get('/', fn() => view('home'));
    Route::get('/home', fn() => view('home'))->name('home');

    // ペア設定
    Route::get('/pea', fn() => view('pea'))->name('pea');
    Route::get('/make', fn() => view('make'))->name('make');
    Route::get('/join', fn() => view('join'))->name('join');
    Route::get('/pair-code-check', [PairCodeCheckController::class, 'show'])->name('pair.code.check');
    Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])->name('pair.code.check.post');

    // 目標
    Route::get('/goals', fn() => view('goals'))->name('goals');
    Route::post('/goals/store', [GoalsController::class, 'store'])->name('goals.store');

    // 進捗確認
    Route::get('/current-goals', [CurrentListController::class, 'index'])->name('current-goals');
    Route::get('/situation', fn() => view('situation'))->name('situation');
    Route::get('/progress', fn() => view('progress'))->name('progress');
});
