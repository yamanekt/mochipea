<?php

// User モデル — usersテーブルに対応
// 例: User::create([...]) → 1行追加  User::find(1) → id=1を取得

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;  // メール認証を使いたい場合に有効にする
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ← ログイン機能を持つ特別なモデル
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable; // HasFactory: テスト用ダミーデータ生成, Notifiable: 通知機能

    /**
     * $fillable — 一括代入を許可するカラム名のリスト
     * User::create(['name' => '...', 'email' => '...']) のように
     * 配列でまとめてデータを入れるとき、ここに書いたカラムだけが許可される。
     * セキュリティ対策（意図しないカラムの書き換えを防ぐ）。
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * $hidden — JSON変換時に隠すカラム
     * APIでユーザー情報を返すとき、パスワードやトークンが漏れないようにする。
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * casts() — カラムの型を自動変換する設定
     * 'email_verified_at' => 'datetime' → 文字列ではなくCarbonオブジェクト（日付操作しやすい）で返す
     * 'password' => 'hashed'            → 値をセットするとき自動的にハッシュ化（暗号化）する
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
