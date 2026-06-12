<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PairCodeCheckController extends Controller
{
    // コード入力画面を表示
    public function show()
    {
        return view('pair_code_check');
    }

    // 子がコードを入力したときの処理
    public function check(Request $request)
    {
        // 入力チェック
        $request->validate([
            'code' => ['required', 'digits:4'],  //requiredは必須処理　//digitsは4文字以外エラー処理
        ], [
            'code.required' => '部屋番号を入力してください。',
            'code.digits' => '部屋番号は4桁で入力してください。',
        ]);

        // ログイン中の子ユーザーID
        $childUserId = Auth::id();

        // 入力された4桁コード
        $code = $request->input('code');

        // pair_codes テーブルからコードを探す
        $pairCode = DB::table('pair_codes')
            ->where('code', $code)
            ->first();

        // コードが存在しない場合
        if (!$pairCode) {
            return back()
                ->withErrors([
                    'code' => '部屋番号が間違っています。',
                ])
                ->withInput();
        }

        // 親ユーザーID
        $parentUserId = $pairCode->user_id;


        // すでにペアが存在しているか確認
        $exists = DB::table('pairs')
            ->where(function ($query) use ($parentUserId, $childUserId) {
                $query->where('user1_id', $parentUserId)
                      ->where('user2_id', $childUserId);
            })
            ->orWhere(function ($query) use ($parentUserId, $childUserId) {
                $query->where('user1_id', $childUserId)
                      ->where('user2_id', $parentUserId);
            })
            ->exists();

        if ($exists) {
            return redirect('/home')
                ->with('success', 'すでにペアに参加しています。');
        }

        // pairs テーブルに登録
        DB::table('pairs')->insert([
            'user1_id' => $parentUserId,
            'user2_id' => $childUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/home')
            ->with('success', 'ペアに参加しました！');
    }
}
