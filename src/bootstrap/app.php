<?php

// app.php — アプリケーションの起動設定ファイル
// ルーティング・ミドルウェア・エラー処理の土台を組み立てる

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))

    // ── ルーティングの読み込み設定 ──
    // web: ブラウザからのアクセス用ルート（routes/web.php）
    // commands: artisanコマンド用ルート（routes/console.php）
    // health: ヘルスチェック用URL（サーバーが生きているか確認する）
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    // ── ミドルウェア設定 ──
    // 未ログインユーザーがauth付きページにアクセスしたとき、
    // 自動的に /login にリダイレクトさせる設定
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => '/login');
    })

    // ── エラーハンドリング設定 ──
    // エラー発生時のカスタム処理を書く場所（今は空）
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
