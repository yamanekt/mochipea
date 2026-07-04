<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PairCodeCheckController extends Controller
{
    /**
     * Join the waiting room created by another account.
     */
    public function check(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:4'],
        ], [
            'code.required' => '部屋番号を入力してください。',
            'code.digits' => '部屋番号は4桁で入力してください。',
        ]);

        $joinUserId = Auth::id();
        $code = $request->input('code');

        $result = DB::transaction(function () use ($code, $joinUserId) {
            $pairCode = DB::table('pair_codes')
                ->where('code', $code)
                ->where('status', 'waiting')
                ->lockForUpdate()
                ->first();

            if (!$pairCode) {
                return ['error' => '部屋番号が間違っているか、すでに使用されています。'];
            }

            $ownerId = (int) $pairCode->user_id;

            if ($ownerId === (int) $joinUserId) {
                return ['error' => '自分で作成した部屋番号では参加できません。別のアカウントで参加してください。'];
            }

            $updated = DB::table('pairs')
                ->where('pair_code', $code)
                ->where('user1_id', $ownerId)
                ->whereColumn('user1_id', 'user2_id')
                ->update([
                    'user2_id' => $joinUserId,
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                return ['error' => 'この部屋番号の目標が見つかりませんでした。'];
            }

            DB::table('pair_codes')
                ->where('id', $pairCode->id)
                ->update([
                    'status' => 'matched',
                    'updated_at' => now(),
                ]);

            return ['redirect' => true];
        });

        if (!empty($result['error'])) {
            return back()
                ->withErrors(['code' => $result['error']])
                ->withInput();
        }

        return redirect('/current-goals')
            ->with('success', '部屋に参加しました。2人の目標を確認してください。');
    }
}
