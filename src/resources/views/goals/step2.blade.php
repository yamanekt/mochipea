@extends('layouts.flow')

@section('title', '目標作成')

@section('content')
    <h1 class="flow-question">何を目標に<br>する？</h1>
    <p class="flow-help">具体的なほど続きます</p>

    <form action="{{ route('goals.new.save', ['step' => 2]) }}" method="post">
        @csrf

        <div class="flow-input">
            <label class="sr-only" for="title">目標名</label>
            <input type="text" id="title" name="title" maxlength="30"
                   value="{{ old('title', $draft['title'] ?? '') }}"
                   placeholder="例：英単語を覚える" autofocus required
                   data-counter-target="title-count">
            <p class="flow-counter"><span id="title-count">0</span> / 30</p>
        </div>

        @error('title')
            <p class="flow-error" role="alert">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-primary flow-submit">次へ</button>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/goal-flow.js'])
@endpush
