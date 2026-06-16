<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountLoginController;

// 認証
Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login']);
Route::post('/logout', [AccountLoginController::class, 'logout'])->name('logout');
Route::get('/register', [AccountLoginController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountLoginController::class, 'register']);

// 画面表示
Route::get('/', fn() => view('home'));
Route::get('/home', fn() => view('home'));
Route::get('/pea', fn() => view('pea'));
Route::get('/make', fn() => view('make'));
Route::get('/join', fn() => view('join'));
Route::get('/goals', fn() => view('goals'));
Route::get('/current-goals', fn() => view('current-goals'));
Route::get('/situation', fn() => view('situation'));
Route::get('/progress', fn() => view('progress'));
