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

/*
|--------------------------------------------------------------------------
| ゲスト用ルート（ログインしていなくてもアクセスできる）
|--------------------------------------------------------------------------
| ログイン画面と新規登録画面は、ログイン前のユーザーも見れる必要がある。
*/
Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');       // ログイン画面を表示
Route::post('/login', [AccountLoginController::class, 'login']);                          // ログインフォーム送信
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register'); // 新規登録画面を表示
Route::post('/register', [AccountRegisterController::class, 'register']);                 // 新規登録フォーム送信

/*
|--------------------------------------------------------------------------
| 認証必須ルート（ログインしていないと /login にリダイレクトされる）
|--------------------------------------------------------------------------
| middleware('auth') → 「ログインしているか？」をチェックする仕組み。
| group() の中に書いたルートは、全部このチェックが適用される。
*/
Route::middleware('auth')->group(function () {

    // ── ログアウト ──
    Route::post('/logout', [AccountLoginController::class, 'logout'])->name('logout');

    // ── ホーム画面 ──
    // fn() => view('home') は「home.blade.php をそのまま表示する」という意味（Controllerを通さない簡易記法）
    Route::get('/', fn() => view('home'));                    // ルートURL → ホーム
    Route::get('/home', fn() => view('home'))->name('home');  // /home でもホーム

    // ── ペア設定 ──
    Route::get('/pea', fn() => view('pea'))->name('pea');     // ペアメニュー画面（「部屋を作る」「部屋に参加」を選ぶ）
    Route::get('/make', fn() => view('make'))->name('make');   // 部屋を作る画面
    Route::get('/join', fn() => view('join'))->name('join');   // 部屋に参加する画面
    Route::get('/pair-code-check', [PairCodeCheckController::class, 'show'])->name('pair.code.check');        // ペアコード入力画面
    Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])->name('pair.code.check.post'); // ペアコード照合処理

    // ── 目標 ──
    Route::get('/goals', fn() => view('goals'))->name('goals');                  // 目標入力画面
    Route::post('/goals/store', [GoalsController::class, 'store'])->name('goals.store'); // 目標をDBに保存

    // ── 進捗確認 ──
    Route::get('/current-goals', [CurrentListController::class, 'index'])->name('current-goals'); // 目標一覧（DBからデータ取得）
    Route::get('/situation', fn() => view('situation'))->name('situation');   // 状況確認画面
    Route::get('/progress', fn() => view('progress'))->name('progress');     // 進捗詳細画面
});
