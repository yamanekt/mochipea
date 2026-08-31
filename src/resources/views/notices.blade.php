@extends('layouts.app')

@section('title', 'お知らせ')

@push('css')
    @vite(['resources/css/goals.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page notices-page">

        <p class="eyebrow">お知らせ</p>
        <h1 class="page-title">最近のできごと</h1>
        <p class="page-lead">ペアの記録と、決着した目標が並びます</p>

        <div class="goal-list">
            @forelse ($notices as $notice)
                <a href="{{ route('situation.show', $notice->goal_id) }}" class="card notice-card">
                    <div class="goal-card-header">
                        <p class="goal-title">{{ $notice->title }}</p>
                        <span class="notice-time">
                            {{ \Carbon\Carbon::parse($notice->at)->diffForHumans() }}
                        </span>
                    </div>

                    <p class="notice-body">{{ $notice->body }}</p>

                    @if ($notice->memo)
                        <p class="notice-memo">{{ $notice->memo }}</p>
                    @endif
                </a>
            @empty
                <div class="card empty-card">
                    <p>お知らせはまだありません。</p>
                </div>
            @endforelse
        </div>

    </main>
@endsection
