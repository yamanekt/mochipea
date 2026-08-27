<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            // accumulate: 0から目標値まで積み上げる（従来の方式）
            // survival  : 目標値を相手のHPとみなし、自分の記録で削っていく
            $table->string('mode', 20)
                  ->default('accumulate')
                  ->after('category');

            // 期限到達時に一度だけ勝敗を確定させ、その結果を保存しておく
            // win / lose / draw / null（未確定）
            $table->string('result', 10)->nullable()->after('status');
            $table->timestamp('finished_at')->nullable()->after('result');
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn(['mode', 'result', 'finished_at']);
        });
    }
};
