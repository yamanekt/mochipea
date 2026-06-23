<?php

// マイグレーション — goalsテーブルの作成
// ペアが設定した目標を保存する（1ペア : 多目標）

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();                          // 目標ID
            $table->foreignId('pair_id')           // どのペアの目標か（pairsテーブルと紐づけ）
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('category');            // カテゴリ（運動/勉強/ゲームなど）
            $table->string('title');               // 目標タイトル（例: 「腕立て伏せ」）
            $table->integer('target_value');        // 目標値（例: 100）
            $table->string('unit');                // 単位（例: 「回」「分」「ページ」）
            $table->date('deadline');              // 期限
            $table->string('status')->default('active'); // 状態: active（進行中）/ finished（終了）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};

