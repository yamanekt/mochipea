<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;
use App\Services\PushNotifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoalProgressController extends Controller
{
    public function show($id)
    {
        $goal = Goal::findOrFail($id);

        $current = GoalProgress::where('goal_id', $id)
            ->sum('value');

        return view('progress', compact('goal', 'current'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'goal_id' => ['required', 'integer', 'exists:goals,id'],
            'value' => ['required', 'integer', 'min:1'],
            'memo' => ['nullable', 'string', 'max:1000'],
            'progress_date' => ['required', 'date'],
        ]);

        GoalProgress::create([
            'goal_id' => $validated['goal_id'],
            'user_id' => auth()->id(),
            'value' => $validated['value'],
            'memo' => $validated['memo'] ?? null,
            'progress_date' => $validated['progress_date'],
        ]);

        $this->notifyPartner(Goal::find($validated['goal_id']), $validated['value']);

        return redirect()->route('situation.show', $validated['goal_id']);
    }

    /**
     * 相手に「記録したよ」と知らせる。
     * 通知が送れなくても記録自体は成功しているので、失敗しても止めない。
     */
    private function notifyPartner(Goal $goal, int $value): void
    {
        $userId = auth()->id();

        $partnerId = DB::table('pairs')
            ->where('id', $goal->pair_id)
            ->select(DB::raw("CASE WHEN user1_id = {$userId} THEN user2_id ELSE user1_id END AS partner_id"))
            ->value('partner_id');

        // ペア未成立（自分が両方に入っている）なら相手はいない
        if (! $partnerId || (int) $partnerId === (int) $userId) {
            return;
        }

        $partner = User::find($partnerId);
        if (! $partner) {
            return;
        }

        $name = auth()->user()->name;
        $body = $goal->isSurvival()
            ? "{$name}さんが{$value}{$goal->unit}削りました！"
            : "{$name}さんが{$value}{$goal->unit}記録しました！";

        try {
            app(PushNotifier::class)->sendToUser(
                $partner,
                $goal->title,
                $body,
                route('situation.show', $goal->id)
            );
        } catch (\Throwable $e) {
            Log::warning('進捗の通知に失敗しました', ['goal_id' => $goal->id, 'error' => $e->getMessage()]);
        }
    }
}
