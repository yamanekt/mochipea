<?php

// マイグレーション — goal_progressテーブルの作成
// 目標に対する毎日の進捗を記録する（例: 腕立て20回、翌日15回 → SUM=35）

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goal_progress', function (Blueprint $table) {
            $table->id();                          // 進捗記録ID
            $table->foreignId('goal_id')           // どの目標に対する進捗か
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('user_id')           // 誰の進捗か
                  ->constrained()
                  ->cascadeOnDelete();
            $table->integer('value');              // 今回の進捗値（例: 20回）
            $table->text('memo')->nullable();      // メモ（任意入力。nullable=NULLを許可）
            $table->date('progress_date');         // 記録した日付
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_progress');
    }
};
