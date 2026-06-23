<?php

// マイグレーション — pairsテーブルの作成
// ペア成立時に2人のユーザーIDを保存する。goalsテーブルのpair_idで参照される

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pairs', function (Blueprint $table) {
            $table->id();                          // ペアID（自動連番）
            $table->foreignId('user1_id')          // 1人目のユーザーID（部屋を作った人）
                  ->constrained('users')           // usersテーブルに紐づける
                  ->cascadeOnDelete();             // ユーザー削除時にペアも削除
            $table->foreignId('user2_id')          // 2人目のユーザーID（参加した人）
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->char('pair_code', 4);          // ペア成立時の4桁コード
            $table->timestamps();                   // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairs');
    }
};
