<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Pair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 目標作成を1問ずつ進めるフロー。
 * 途中でDBに書くと中断時にゴミが残るため、最後の確認まではセッションに貯める。
 */
class GoalFlowController extends Controller
{
    private const KEY = 'goal_draft';
    private const LAST_STEP = 5;

    public const CATEGORIES = [
        'exercise' => '運動',
        'study'    => '勉強',
        'game'     => 'ゲーム',
        'other'    => 'その他',
    ];

    public const UNITS = ['回', '語', '分', 'ページ'];

    /** 各ステップで確定する項目 */
    private const FIELDS = [
        1 => ['category'],
        2 => ['title'],
        3 => ['target_value', 'unit'],
        4 => ['deadline'],
    ];

    public function show(int $step)
    {
        $step = max(1, min(self::LAST_STEP, $step));
        $draft = session(self::KEY, []);

        // 手前のステップが埋まっていなければ、埋まっている最後まで戻す
        for ($i = 1; $i < $step; $i++) {
            foreach (self::FIELDS[$i] as $field) {
                if (blank($draft[$field] ?? null)) {
                    return redirect()->route('goals.new.step', ['step' => $i]);
                }
            }
        }

        return view("goals.step{$step}", [
            'step'  => $step,
            'draft' => $draft,
        ]);
    }

    public function save(Request $request, int $step)
    {
        $step = max(1, min(self::LAST_STEP - 1, $step));
        $validated = $request->validate($this->rules($step), $this->messages());

        // 単位は選択肢か自由入力のどちらか
        if ($step === 3) {
            $validated['unit'] = $validated['unit'] === 'other'
                ? $request->input('unit_custom')
                : $validated['unit'];
            unset($validated['unit_custom']);
        }

        session([self::KEY => array_merge(session(self::KEY, []), $validated)]);

        return redirect()->route('goals.new.step', ['step' => $step + 1]);
    }

    /**
     * 確認画面から確定。ここで初めてDBに書く。
     */
    public function store()
    {
        $draft = session(self::KEY, []);

        foreach (self::FIELDS as $fields) {
            foreach ($fields as $field) {
                if (blank($draft[$field] ?? null)) {
                    return redirect()->route('goals.new.step', ['step' => 1]);
                }
            }
        }

        $ownerId = auth()->id();

        $roomId = DB::transaction(function () use ($draft, $ownerId) {
            $roomId = $this->generateRoomCode();

            DB::table('rooms')->insert([
                'room_id'    => $roomId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pair = Pair::create([
                'user1_id'  => $ownerId,
                'user2_id'  => $ownerId,
                'pair_code' => $roomId,
            ]);

            DB::table('pair_codes')->insert([
                'user_id'    => $ownerId,
                'code'       => $roomId,
                'status'     => 'waiting',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Goal::create([
                'pair_id'      => $pair->id,
                'category'     => $draft['category'],
                'title'        => $draft['title'],
                'target_value' => $draft['target_value'],
                'unit'         => $draft['unit'],
                'deadline'     => $draft['deadline'],
            ]);

            return $roomId;
        });

        session()->forget(self::KEY);

        // 相手に番号を伝えないとペアが成立しないので、部屋番号の表示へ送る
        return redirect()->route('make');
    }

    /** 中断してペア設定へ戻る */
    public function cancel()
    {
        session()->forget(self::KEY);

        return redirect()->route('pea');
    }

    private function rules(int $step): array
    {
        return match ($step) {
            1 => ['category' => ['required', 'in:' . implode(',', array_keys(self::CATEGORIES))]],
            2 => ['title' => ['required', 'string', 'max:30']],
            3 => [
                'target_value' => ['required', 'integer', 'min:1', 'max:99999'],
                'unit'         => ['required', 'string'],
                'unit_custom'  => ['required_if:unit,other', 'nullable', 'string', 'max:10'],
            ],
            4 => ['deadline' => ['required', 'date', 'after_or_equal:today']],
            default => [],
        };
    }

    private function messages(): array
    {
        return [
            'category.required'     => 'カテゴリを選んでください。',
            'title.required'        => '目標名を入力してください。',
            'title.max'             => '目標名は30文字以内で入力してください。',
            'target_value.required' => '目標値を入力してください。',
            'target_value.integer'  => '目標値は数字で入力してください。',
            'unit.required'         => '単位を選んでください。',
            'unit_custom.required_if' => '単位を入力してください。',
            'deadline.required'     => '期限を選んでください。',
            'deadline.after_or_equal' => '期限は今日以降の日付にしてください。',
        ];
    }

    private function generateRoomCode(): string
    {
        do {
            $roomId = (string) random_int(1000, 9999);
        } while (
            DB::table('rooms')->where('room_id', $roomId)->exists()
            || DB::table('pair_codes')
                ->where('code', $roomId)
                ->where('status', 'waiting')
                ->exists()
        );

        return $roomId;
    }
}
