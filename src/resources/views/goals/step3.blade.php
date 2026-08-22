@extends('layouts.flow')

@section('title', '目標作成')

@php
    $units = \App\Http\Controllers\GoalFlowController::UNITS;
    $currentUnit = old('unit', $draft['unit'] ?? '');
    $isCustomUnit = $currentUnit !== '' && ! in_array($currentUnit, $units, true);
@endphp

@section('content')
    <h1 class="flow-question">どれくらい<br>やる？</h1>
    <p class="flow-help">合計の数を入れてください</p>

    <form action="{{ route('goals.new.save', ['step' => 3]) }}" method="post">
        @csrf

        <div class="flow-input flow-input-number">
            <label class="sr-only" for="target_value">目標値</label>
            <input type="text" id="target_value" name="target_value"
                   inputmode="numeric" pattern="[0-9]*" maxlength="5"
                   value="{{ old('target_value', $draft['target_value'] ?? '') }}"
                   placeholder="100" autofocus required
                   data-numeric-only>
        </div>

        <p class="flow-sublabel">単位</p>

        <div class="chip-group flow-chips">
            @foreach ($units as $unit)
                <label class="chip">
                    <input type="radio" name="unit" value="{{ $unit }}"
                           {{ $currentUnit === $unit ? 'checked' : '' }} required>
                    {{ $unit }}
                </label>
            @endforeach

            {{-- その他を選んだときだけ自由入力を出す --}}
            <label class="chip">
                <input type="radio" name="unit" value="other"
                       {{ $isCustomUnit ? 'checked' : '' }}
                       data-reveal="unit-custom">
                その他
            </label>
        </div>

        <div class="flow-input flow-custom" id="unit-custom" @unless ($isCustomUnit) hidden @endunless>
            <label class="sr-only" for="unit_custom">単位（自由入力）</label>
            <input type="text" id="unit_custom" name="unit_custom" maxlength="10"
                   value="{{ old('unit_custom', $isCustomUnit ? $currentUnit : '') }}"
                   placeholder="例：km">
        </div>

        @error('target_value')
            <p class="flow-error" role="alert">{{ $message }}</p>
        @enderror
        @error('unit')
            <p class="flow-error" role="alert">{{ $message }}</p>
        @enderror
        @error('unit_custom')
            <p class="flow-error" role="alert">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-primary flow-submit">次へ</button>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/goal-flow.js'])
@endpush
