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

        <button type="submit" class="btn-primary flow-submit">次へ</button>
    </form>
@endsection
