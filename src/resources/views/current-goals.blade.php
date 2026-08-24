@extends('layouts.app')
@section('title', '目標一覧')
@push('css') @vite(['resources/css/goals.css']) @endpush
@section('content')
<div class="page-bg"></div><main class="page goals-page">
<p class="eyebrow">目標一覧</p><h1 class="page-title">いま頑張っている目標</h1><p class="page-lead">ペアと進み具合を見比べながら続けよう</p>
<form method="GET" action="{{ route('current-goals') }}" class="sort-form" aria-label="並び順"><label class="sort-option"><input type="radio" name="sort" value="updated" {{ $sort === 'updated' ? 'checked' : '' }} onchange="this.form.submit()">更新順</label><label class="sort-option"><input type="radio" name="sort" value="created" {{ $sort === 'created' ? 'checked' : '' }} onchange="this.form.submit()">作成順</label></form>
<div class="goal-list">@forelse ($goals as $goal) @php $progressRate = min(100, max(0, $goal->progress_rate)); @endphp
<article class="goal-card"><div class="goal-card-header"><div><p class="goal-category">{{ \App\Http\Controllers\GoalFlowController::CATEGORIES[$goal->category] ?? $goal->category }}</p><h2 class="goal-title">{{ $goal->title }}</h2></div><span class="status-badge {{ $goal->display_status === '期限切れ' ? 'status-expired' : 'status-active' }}">{{ $goal->display_status }}</span></div><p class="goal-partner">ペア：{{ $goal->partner_name }}</p><div class="progress-block"><div class="progress-label"><span>{{ $goal->current_value }} / {{ $goal->target_value }}{{ $goal->unit }}</span><strong>{{ $goal->progress_rate }}%</strong></div><div class="progress-track"><span class="progress-fill" style="width: {{ $progressRate }}%"></span></div></div><div class="goal-card-footer"><span>期限 {{ \Carbon\Carbon::parse($goal->deadline)->format('n月j日') }}</span><a href="{{ route('situation.show', $goal->id) }}">詳細を見る <span aria-hidden="true">›</span></a></div></article>
@empty <div class="empty-card"><img src="{{ asset('images/IMG_0639.png') }}" alt=""><p>まだ目標がありません</p><a href="{{ route('goals') }}" class="btn-primary">目標をつくる</a></div>@endforelse</div></main>
@endsection

