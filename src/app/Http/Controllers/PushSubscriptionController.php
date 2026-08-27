<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * ブラウザで作った購読情報を保存する。
     * 同じ端末から何度呼ばれても1件しか作らない。
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint'  => ['required', 'string', 'max:1000'],
            'publicKey' => ['required', 'string', 'max:255'],
            'authToken' => ['required', 'string', 'max:255'],
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint_hash' => PushSubscription::hashEndpoint($data['endpoint'])],
            [
                'user_id'    => auth()->id(),
                'endpoint'   => $data['endpoint'],
                'public_key' => $data['publicKey'],
                'auth_token' => $data['authToken'],
            ]
        );

        return response()->json(['ok' => true]);
    }

    /** 通知をオフにしたときに購読を消す */
    public function destroy(Request $request): JsonResponse
    {
        $data = $request->validate(['endpoint' => ['required', 'string', 'max:1000']]);

        PushSubscription::where('endpoint_hash', PushSubscription::hashEndpoint($data['endpoint']))
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(['ok' => true]);
    }
}
