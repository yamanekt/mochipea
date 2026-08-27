@extends('layouts.flow')

@section('title', '目標作成')

@section('content')
    <h1 class="flow-question">どんなことを<br>がんばる？</h1>
    <p class="flow-help">あとから変更できます</p>

    <form action="{{ route('goals.new.save', ['step' => 1]) }}" method="post">
        @csrf

        <div class="chip-group flow-chips">
            @foreach (\App\Http\Controllers\GoalFlowController::CATEGORIES as $value => $label)
                <label class="chip">
                    <input type="radio" name="category" value="{{ $value }}"
                           {{ ($draft['category'] ?? '') === $value ? 'checked' : '' }} required>
                    {{ $label }}
                </label>
            @endforeach
        </div>

        @error('category')
            <p class="flow-error" role="alert">{{ $message }}</p>
        @enderror

        {{-- モード選択。STEP3の「単位」と同じパーツを流用している --}}
        <p class="flow-sublabel">すすめかた</p>

        <div class="chip-group flow-chips">
            @foreach (\App\Models\Goal::MODES as $value => $label)
                <label class="chip">
                    <input type="radio" name="mode" value="{{ $value }}"
                           {{ ($draft['mode'] ?? \App\Models\Goal::MODE_ACCUMULATE) === $value ? 'checked' : '' }} required>
                    {{ $label }}
                </label>
            @endforeach
        </div>

        <p class="flow-mode-help">
            @foreach (\App\Models\Goal::MODE_DESCRIPTIONS as $value => $desc)
                <span data-mode-help="{{ $value }}">{{ $desc }}</span>
            @endforeach
        </p>

        @error('mode')
            <p class="flow-error" role="alert">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-primary flow-submit">次へ</button>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/goal-flow.js'])
@endpush
