@extends('layouts.flow')

@section('title', '目標作成')

@php
    $categories = \App\Http\Controllers\GoalFlowController::CATEGORIES;
    $modes = \App\Models\Goal::MODES;
    $mode = $draft['mode'] ?? \App\Models\Goal::MODE_ACCUMULATE;
    $rows = [
        ['label' => 'カテゴリ', 'value' => $categories[$draft['category']] ?? $draft['category'], 'step' => 1],
        ['label' => 'すすめかた', 'value' => $modes[$mode] ?? $mode, 'step' => 1],
        ['label' => '目標名',   'value' => $draft['title'], 'step' => 2],
        ['label' => '目標値',   'value' => $draft['target_value'] . ' ' . $draft['unit'], 'step' => 3],
        ['label' => '期限',     'value' => \Carbon\Carbon::parse($draft['deadline'])->format('Y / m / d'), 'step' => 4],
    ];
@endphp

@section('content')
    <h1 class="flow-question">これでいい？</h1>
    <p class="flow-help">決定するとペア相手にも通知されます</p>

    <div class="card summary-card">
        @foreach ($rows as $row)
            <div class="row">
                <span class="row-label">{{ $row['label'] }}</span>
                <span class="row-value">
                    <span>{{ $row['value'] }}</span>
                    <a href="{{ route('goals.new.step', ['step' => $row['step']]) }}"
                       class="summary-edit">変更</a>
                </span>
            </div>
        @endforeach
    </div>

    <form action="{{ route('goals.new.store') }}" method="post">
        @csrf
        <button type="submit" class="btn-primary flow-submit">この目標を決定する</button>
    </form>
@endsection
