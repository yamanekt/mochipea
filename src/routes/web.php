<?php

// web.php — ルーティング定義ファイル
// 「どのURLにアクセスしたら、どの処理（Controller）を呼ぶか」を決める
// Route::get(URL, 処理) → ページを見る / Route::post(URL, 処理) → フォーム送信
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountLoginController;
use App\Http\Controllers\AccountRegisterController;
use App\Http\Controllers\AccountSettingController;
use App\Http\Controllers\CurrentListController;
use App\Http\Controllers\GoalResultController;
use App\Http\Controllers\PairCodeCheckController;
use App\Http\Controllers\SituationController;
use App\Http\Controllers\GoalProgressController;
use App\Http\Controllers\TimeLineController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\GoalFlowController;
use App\Http\Controllers\PairRoomController;
use Illuminate\Support\Facades\DB;


// ── ゲスト用ルート（ログインしていなくてもアクセスできる）──
Route::get('/login', [AccountLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountLoginController::class, 'login'])
    // 総当たり対策。5回/分を超えたら 429 を返す
    ->middleware('throttle:5,1');
Route::get('/register', [AccountRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountRegisterController::class, 'register']);

// ── 認証必須ルート（ログインしていないと /login にリダイレクト）──
Route::middleware('auth')->group(function () {

    // ログアウト
    Route::post('/logout', [AccountLoginController::class, 'logout'])->name('logout');

    // ホーム
    Route::get('/', fn() => view('home'))->name('home');
    // /home は名前を持たせず、正規のURL（/ ＝ route('home')）へ寄せる
    Route::get('/home', fn() => redirect()->route('home'));

    // ペア設定メニュー
    Route::get('/pea', [PairRoomController::class, 'index'])->name('pea');

    // 発行済みで、まだ相手が参加していない部屋
    Route::get('/pea/waiting', [PairRoomController::class, 'waiting'])->name('pair.waiting');
    Route::post('/pea/waiting/cancel', [PairRoomController::class, 'cancel'])->name('pair.waiting.cancel');

    // 目標作成（1問ずつ進めるフロー）
    Route::get('/goals', fn() => redirect()->route('goals.new.step', ['step' => 1]))->name('goals');
    Route::prefix('goals/new')->name('goals.new.')->group(function () {
        Route::get('/{step}', [GoalFlowController::class, 'show'])->whereNumber('step')->name('step');
        Route::post('/{step}', [GoalFlowController::class, 'save'])->whereNumber('step')->name('save');
        Route::post('/', [GoalFlowController::class, 'store'])->name('store');
        Route::post('/cancel', [GoalFlowController::class, 'cancel'])->name('cancel');
    });

    // 部屋を作った後のコード表示。
    // セッションではなくDBから引くので、リロードでも直リンクでも壊れない
    Route::get('/make', function () {
        $code = DB::table('pair_codes')
            ->where('user_id', auth()->id())
            ->where('status', 'waiting')
            ->latest('id')
            ->first();

        if (! $code) {
            return redirect()->route('pea');
        }

        return view('make', ['room' => (object) ['room_id' => $code->code]]);
    })->name('make');

    // 部屋を探す（コード入力）
    Route::get('/join', fn() => view('join'))->name('join');
    Route::post('/pair-code-check', [PairCodeCheckController::class, 'check'])->name('pair.code.check.post');

    // 目標一覧（進捗付き）
    Route::get('/current-goals', [CurrentListController::class, 'index'])->name('current-goals');

    // 決着済みの目標（対戦履歴）
    Route::get('/results', [GoalResultController::class, 'index'])->name('goal.results');

    // お知らせ（既存データから組み立てる）
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices');

    // アカウント設定（マイページから開く）
    Route::get('/account/profile', [AccountSettingController::class, 'editProfile'])->name('account.profile');
    Route::patch('/account/profile', [AccountSettingController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/password', [AccountSettingController::class, 'editPassword'])->name('account.password');
    Route::patch('/account/password', [AccountSettingController::class, 'updatePassword'])->name('account.password.update');
    Route::get('/account/delete', [AccountSettingController::class, 'confirmDelete'])->name('account.delete');
    Route::delete('/account', [AccountSettingController::class, 'destroy'])->name('account.destroy');


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
    // マイページ（戦績＋アカウント）
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');

});

