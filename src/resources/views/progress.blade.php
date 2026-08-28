@extends('layouts.app')

@section('title', '達成入力')

@push('css')
    @vite(['resources/css/progress.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page progress-page">

        <a href="{{ route('situation.show', $goal->id) }}" class="page-back">← 目標にもどる</a>

        <p class="eyebrow">達成入力</p>
        <h1 class="page-title">今日はどれくらい？</h1>
        <p class="page-lead">続けた分だけ相手との差がつきます</p>

        {{-- いまの状況を先に出して、あとどれくらいかが分かるようにする --}}
        <div class="card progress-goal-card">
            <span class="card-label">{{ $goal->title }}</span>
            <p class="progress-current">
                <strong>{{ $current }}</strong>
                <span>/ {{ $goal->target_value }}{{ $goal->unit }}</span>
            </p>
        </div>

        @if ($errors->any())
            <ul class="auth-error-list" role="alert">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('progress.store') }}" method="POST" class="progress-form">
            @csrf
            <input type="hidden" name="goal_id" value="{{ $goal->id }}">

            <div class="field">
                <label class="field-label" for="value">達成数</label>
                <input type="text" id="value" name="value"
                       inputmode="numeric" pattern="[0-9]*" maxlength="5"
                       value="{{ old('value') }}"
                       placeholder="例：10" autofocus required data-numeric-only>
            </div>

            <div class="field">
                <label class="field-label" for="memo">メモ（任意）</label>
                <textarea id="memo" name="memo" rows="4"
                          placeholder="今日の内容や感想">{{ old('memo') }}</textarea>
            </div>

            <div class="field">
                <label class="field-label" for="progress_date">達成日</label>
                <input type="date" id="progress_date" name="progress_date"
                       value="{{ old('progress_date', now()->toDateString()) }}" required>
            </div>

            <button type="submit" class="btn-primary">記録する</button>
        </form>

        <img src="{{ asset('images/IMG_0641.png') }}" alt="" class="mascot progress-mascot">

    </main>
@endsection

@push('scripts')
    @vite(['resources/js/goal-flow.js'])
@endpush
