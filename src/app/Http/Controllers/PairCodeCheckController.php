<?php

// ペアコード照合（部屋に参加する処理）

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // ログイン中のユーザー情報を取得
use Illuminate\Support\Facades\DB;    // DBファサード（SQLに近い形でDBを操作する方法）

class PairCodeCheckController extends Controller
{
    /**
     * コード入力画面を表示
     * Route: GET /pair-code-check
     */
    public function show()
    {
        return view('pair_code_check');
    }

    /**
     * ペアコード照合処理（User Bがコードを入力して送信したとき）
     * Route: POST /pair-code-check
     */
    public function check(Request $request)
    {
        // ─バリデーション
        // digits:4 → ちょうど4桁の数字のみ許可
        $request->validate([
            'code' => ['required', 'digits:4'],
        ], [
            'code.required' => '部屋番号を入力してください。',
            'code.digits' => '部屋番号は4桁で入力してください。',
        ]);

        // 現在のユーザー情報を取得
        $childUserId = Auth::id();      // ログイン中のユーザーID（= 参加する側 = User B）
        $code = $request->input('code'); // フォームから送られた4桁コード

        // pair_codesテーブルからコードを検索
        // DB::table() → Eloquentモデルを使わず直接テーブルを操作する方法
        // ->where('code', $code) → WHERE code = '入力されたコード'
        // ->first() → 最初の1件を取得（見つからなければ null）
        $pairCode = DB::table('pair_codes')
            ->where('code', $code)
            ->first();

        // コードが見つからなかった場合
        if (!$pairCode) {
            return back()                   // 前の画面に戻る
                ->withErrors([
                    'code' => '部屋番号が間違っています。',
                ])
                ->withInput();              // 入力値を保持
        }

        // 親ユーザー（部屋を作った人）のIDを取得
        $parentUserId = $pairCode->user_id;

        // すでにペアが存在しているかチェック
        // 同じ2人の組み合わせがpairsテーブルにあるか確認（順番が逆でもOK）
        // use ($変数) → クロージャ（無名関数）の中で外側の変数を使うための書き方
        $exists = DB::table('pairs')
            ->where(function ($query) use ($parentUserId, $childUserId) {
                // パターン1: user1=親, user2=子
                $query->where('user1_id', $parentUserId)
                      ->where('user2_id', $childUserId);
            })
            ->orWhere(function ($query) use ($parentUserId, $childUserId) {
                // パターン2: user1=子, user2=親（逆パターン）
                $query->where('user1_id', $childUserId)
                      ->where('user2_id', $parentUserId);
            })
            ->exists(); // true/false を返す

        // すでにペアなら重複登録しない
        if ($exists) {
            return redirect('/home')
                ->with('success', 'すでにペアに参加しています。');
        }

        // pairsテーブルに新しいペアを登録
        DB::table('pairs')->insert([
            'user1_id' => $parentUserId,   // 部屋を作った人
            'user2_id' => $childUserId,    // 参加した人
            'created_at' => now(),          // 現在日時
            'updated_at' => now(),
        ]);

        return redirect('/home')
            ->with('success', 'ペアに参加しました！');
    }
}
