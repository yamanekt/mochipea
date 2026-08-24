@extends('layouts.app')
@section('title', 'タイムライン')
@push('css') @vite(['resources/css/goals.css']) @endpush
@section('content')
<div class="page-bg"></div><main class="page timeline-page"><p class="eyebrow">タイムライン</p><h1 class="page-title">みんなのがんばり</h1><p class="page-lead">ペアの進捗を応援しよう</p><div class="timeline-list">@forelse ($entries as $entry)<article class="timeline-card"><div class="timeline-avatar">{{ mb_substr($entry->user_name, 0, 1) }}</div><div class="timeline-body"><div class="timeline-meta"><strong>{{ $entry->user_name }}</strong><time>{{ \Carbon\Carbon::parse($entry->created_at)->diffForHumans() }}</time></div><p class="timeline-goal">{{ $entry->title }}</p><p class="timeline-value">{{ $entry->value }}<small>{{ $entry->unit }}</small> がんばった！</p>@if ($entry->memo)<p class="timeline-memo">{{ $entry->memo }}</p>@endif<a href="{{ route('situation.show', $entry->goal_id) }}" class="timeline-link">目標の状況を見る</a></div></article>@empty <div class="empty-card"><img src="{{ asset('images/IMG_0639.png') }}" alt=""><p>まだ進捗の投稿がありません</p></div>@endforelse</div></main>
@endsection
