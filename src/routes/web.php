<?php

// web.php — ルーティング定義ファイル
// 「どのURLにアクセスしたら、どの処理（Controller）を呼ぶか」を決める
// Route::get(URL, 処理) → ページを見る / Route::post(URL, 処理) → フォーム送信
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountLoginController;
use App\Http\Controllers\AccountRegisterController;
use App\Http\Controllers\CurrentListController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\PairCodeCheckController;
use App\Http\Controllers\SituationController;
use App\Http\Controllers\GoalProgressController;
use App\Http\Controllers\TimeLineController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\BackgroundController;


// ── ゲスト用ルート（ログインしていなくてもアクセスできる）──
Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login']);
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountRegisterController::class, 'register']);

// ── 認証必須ルート（ログインしていないと /login にリダイレクト）──
Route::middleware('auth')->group(function () {

    // ログアウト
    Route::post('/logout', [AccountLoginController::class, 'logout'])->name('logout');

    // ホーム
    Route::get('/', fn() => view('home'))->name('home');
    Route::get('/home', fn() => view('home'));

    // ペア設定メニュー
    Route::get('/pea', fn() => view('pea'))->name('pea');

    // 部屋を作る → 目標登録画面へ
    Route::get('/goals', fn() => view('goals'))->name('goals');
    Route::post('/goals/store', [GoalsController::class, 'store'])->name('goals.store');

    // 部屋を作った後のコード表示
    Route::get('/make', fn() => view('make'))->name('make');

    // 部屋を探す（コード入力）
    Route::get('/join', fn() => view('join'))->name('join');
    Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])->name('pair.code.check.post');

    // 目標一覧（進捗付き）
    Route::get('/current-goals', [CurrentListController::class, 'index'])->name('current-goals');

    // 進行中の目標（タイムライン）
    Route::get('/timeline', [TimeLineController::class, 'index'])->name('timeline');

    // その他画面


    Route::get('/situation/{id}', [SituationController::class, 'show'])
        ->name('situation.show');

    // 達成入力
    Route::get('/progress/{id}', [GoalProgressController::class, 'show'])
        ->name('progress.show');

    Route::post('/progress', [GoalProgressController::class, 'store'])
        ->name('progress.store');
    Route::get('/mypage', function () {
    return view('mypage');
})->name('mypage');


});

Route::get('/current-goals/{id}', [GoalsController::class, 'show'])
    ->name('goals.show');
