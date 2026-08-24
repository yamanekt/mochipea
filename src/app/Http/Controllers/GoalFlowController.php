<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Pair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoalFlowController extends Controller
{
    private const KEY = 'goal_draft';
    public const CATEGORIES = ['exercise' => '運動', 'study' => '勉強', 'game' => 'ゲーム', 'other' => 'その他'];
    public const UNITS = ['回', '語', '分', 'ページ'];

    public function show(int $step)
    {
        $step = max(1, min(5, $step));
        $draft = session(self::KEY, []);
        $needed = [1 => 'category', 2 => 'title', 3 => 'target_value', 4 => 'deadline'];
        foreach ($needed as $previous => $field) {
            if ($previous < $step && blank($draft[$field] ?? null)) return redirect()->route('goals.new.step', ['step' => $previous]);
        }
        return view("goals.step{$step}", compact('step', 'draft'));
    }

    public function save(Request $request, int $step)
    {
        $rules = match ($step) {
            1 => ['category' => ['required', 'in:' . implode(',', array_keys(self::CATEGORIES))]],
            2 => ['title' => ['required', 'string', 'max:30']],
            3 => ['target_value' => ['required', 'integer', 'min:1', 'max:99999'], 'unit' => ['required', 'string'], 'unit_custom' => ['nullable', 'string', 'max:10']],
            4 => ['deadline' => ['required', 'date', 'after_or_equal:today']],
            default => [],
        };
        abort_if($rules === [], 404);
        $data = $request->validate($rules);
        if ($step === 3 && $data['unit'] === 'other') $data['unit'] = $data['unit_custom'] ?? '';
        unset($data['unit_custom']);
        session([self::KEY => array_merge(session(self::KEY, []), $data)]);
        return redirect()->route('goals.new.step', ['step' => $step + 1]);
    }

    public function store()
    {
        $draft = session(self::KEY, []);
        foreach (['category', 'title', 'target_value', 'unit', 'deadline'] as $field) if (blank($draft[$field] ?? null)) return redirect()->route('goals.new.step', ['step' => 1]);
        $ownerId = auth()->id();
        DB::transaction(function () use ($draft, $ownerId) {
            do { $code = (string) random_int(1000, 9999); } while (DB::table('pair_codes')->where('code', $code)->where('status', 'waiting')->exists());
            DB::table('rooms')->insert(['room_id' => $code, 'created_at' => now(), 'updated_at' => now()]);
            $pair = Pair::create(['user1_id' => $ownerId, 'user2_id' => $ownerId, 'pair_code' => $code]);
            DB::table('pair_codes')->insert(['user_id' => $ownerId, 'code' => $code, 'status' => 'waiting', 'created_at' => now(), 'updated_at' => now()]);
            Goal::create(['pair_id' => $pair->id, 'category' => $draft['category'], 'title' => $draft['title'], 'target_value' => $draft['target_value'], 'unit' => $draft['unit'], 'deadline' => $draft['deadline']]);
        });
        session()->forget(self::KEY);
        return redirect()->route('make');
    }

    public function cancel() { session()->forget(self::KEY); return redirect()->route('pea'); }
}

