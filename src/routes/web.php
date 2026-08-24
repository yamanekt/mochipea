<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AccountLoginController;
use App\Http\Controllers\AccountRegisterController;
use App\Http\Controllers\CurrentListController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\PairCodeCheckController;
use App\Http\Controllers\SituationController;
use App\Http\Controllers\GoalProgressController;
use App\Http\Controllers\TimeLineController;
use App\Http\Controllers\GoalFlowController;
use App\Http\Controllers\PairRoomController;

Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login']);
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountRegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AccountLoginController::class, 'logout'])->name('logout');
    Route::get('/', fn () => view('home'))->name('home');
    Route::get('/home', fn () => redirect()->route('home'));

    Route::get('/pea', [PairRoomController::class, 'index'])->name('pea');
    Route::get('/pea/waiting', [PairRoomController::class, 'waiting'])->name('pair.waiting');

    Route::get('/goals', fn () => redirect()->route('goals.new.step', ['step' => 1]))->name('goals');
    Route::prefix('goals/new')->name('goals.new.')->group(function () {
        Route::get('/{step}', [GoalFlowController::class, 'show'])->whereNumber('step')->name('step');
        Route::post('/{step}', [GoalFlowController::class, 'save'])->whereNumber('step')->name('save');
        Route::post('/', [GoalFlowController::class, 'store'])->name('store');
        Route::post('/cancel', [GoalFlowController::class, 'cancel'])->name('cancel');
    });

    Route::get('/make', function () {
        $code = DB::table('pair_codes')->where('user_id', auth()->id())->where('status', 'waiting')->latest('id')->first();
        return $code ? view('make', ['room' => (object) ['room_id' => $code->code]]) : redirect()->route('pea');
    })->name('make');
    Route::get('/join', fn () => view('join'))->name('join');
    Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])->name('pair.code.check.post');
    Route::get('/current-goals', [CurrentListController::class, 'index'])->name('current-goals');
    Route::get('/timeline', [TimeLineController::class, 'index'])->name('timeline');
    Route::get('/mypage', fn () => view('mypage'))->name('mypage');
    Route::get('/situation/{id}', [SituationController::class, 'show'])->name('situation.show');
    Route::get('/progress/{id}', [GoalProgressController::class, 'show'])->name('progress.show');
    Route::post('/progress', [GoalProgressController::class, 'store'])->name('progress.store');
});

Route::get('/current-goals/{id}', [GoalsController::class, 'show'])->name('goals.show');
