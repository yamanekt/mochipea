@extends('layouts.app')
@section('title', '進捗を記録')
@push('css') @vite(['resources/css/progress.css']) @endpush
@section('content')
<div class="page-bg"></div><main class="page progress-page"><a href="{{ route('situation.show', $goal->id) }}" class="page-back">‹ <span>目標の状況へ</span></a><p class="eyebrow">進捗を記録</p><h1 class="page-title">今日のがんばりを残そう</h1><div class="progress-goal-card"><p>{{ $goal->title }}</p><strong>{{ $current }} <small>/ {{ $goal->target_value }}{{ $goal->unit }}</small></strong></div><form action="{{ route('progress.store') }}" method="POST" class="progress-form">@csrf<input type="hidden" name="goal_id" value="{{ $goal->id }}"><label>今回の進捗 <span>{{ $goal->unit }}</span><input type="number" name="value" min="1" inputmode="numeric" placeholder="例：10" required></label><label>ひとこと <span>任意</span><textarea name="memo" rows="4" placeholder="今日がんばったことを書いてみよう"></textarea></label><label>記録した日<input type="date" name="progress_date" value="{{ old('progress_date', now()->toDateString()) }}" required></label><img src="{{ asset('images/IMG_0641.png') }}" alt="" class="progress-mascot"><button type="submit" class="btn-primary">記録する</button></form></main>
@endsection

