<?php

// マイグレーション — pair_codesテーブルの作成
// User Aが「部屋を作る」ときに4桁コードを発行 → User Bが入力してペア成立

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;   // テーブルのカラム定義に使うクラス
use Illuminate\Support\Facades\Schema;      // テーブルの作成・削除に使うクラス

return new class extends Migration
{
    /**
     * up() — テーブルを作成する（migrate時に実行）
     */
    public function up(): void
    {
        Schema::create('pair_codes', function (Blueprint $table) {
            $table->id();                   // 自動連番のID（主キー）
            $table->foreignId('user_id')    // 外部キー: usersテーブルのidと紐づく（部屋を作ったユーザー）
                  ->constrained()           // → usersテーブルに存在するIDしか入れられない
                  ->cascadeOnDelete();      // → ユーザーが削除されたらこの行も自動削除
            $table->char('code', 4);        // 4桁の部屋コード（例: "1234"）
            $table->string('status')->default('waiting'); // 状態: waiting（待機中）/ matched（成立済み）
            $table->timestamps();           // created_at と updated_at を自動生成
        });
    }

    /**
     * down() — テーブルを削除する（rollback時に実行）
     */
    public function down(): void
    {
        Schema::dropIfExists('pair_codes');
    }
};
