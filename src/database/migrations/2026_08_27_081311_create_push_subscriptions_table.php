<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // ブラウザが発行する購読情報。endpoint が実質の宛先になる。
            // 端末ごとに違うので、1ユーザーが複数持てる（スマホとPCなど）
            $table->text('endpoint');
            $table->string('public_key');   // p256dh
            $table->string('auth_token');   // auth

            $table->timestamps();

            // 同じ端末を二重登録しないための一意制約。
            // endpoint は長すぎて索引に入らないのでハッシュ化した列で持つ
            $table->string('endpoint_hash', 64)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
