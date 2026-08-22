@extends('layouts.flow')

@section('title', '目標作成')

@php
    $current = old('deadline', $draft['deadline'] ?? '');
    $quick = [
        '1週間' => now()->addWeek()->toDateString(),
        '1か月' => now()->addMonth()->toDateString(),
        '3か月' => now()->addMonths(3)->toDateString(),
    ];
@endphp

@section('content')
    <h1 class="flow-question">いつまでに<br>達成する？</h1>
    <p class="flow-help">期限があるほど続きやすくなります</p>

    <form action="{{ route('goals.new.save', ['step' => 4]) }}" method="post">
        @csrf

        <div class="flow-input">
            <label class="sr-only" for="deadline">期限</label>
            <input type="date" id="deadline" name="deadline"
                   value="{{ $current }}"
                   min="{{ now()->toDateString() }}" required>
        </div>

        {{-- よく使う期間はワンタップで入れられるようにする --}}
        <div class="chip-group flow-chips">
            @foreach ($quick as $label => $date)
                <button type="button" class="chip"
                        data-set-date="{{ $date }}" data-target="deadline">{{ $label }}</button>
            @endforeach
        </div>

        @error('deadline')
            <p class="flow-error" role="alert">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-primary flow-submit">次へ</button>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/goal-flow.js'])
@endpush
