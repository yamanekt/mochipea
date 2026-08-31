@extends('layouts.app')

@section('title', 'タイムライン')

@push('css')
    @vite(['resources/css/goals.css'])
@endpush

@section('content')
    <div class="page-bg"></div>

    <main class="page timeline-page">

        <p class="eyebrow">タイムライン</p>
        <h1 class="page-title">みんなの記録</h1>
        <p class="page-lead">ペアのがんばりを見て、自分も続けよう</p>

        <div class="timeline-list">
            @forelse ($entries as $entry)
                {{-- 左のカラーバーでカテゴリを示す --}}
                <article class="card timeline-card category-{{ $entry->category ?? 'other' }}">
                    <div class="timeline-body">
                        <div class="timeline-head">
                            <div class="timeline-who">
                                <span class="timeline-avatar">
                                    <img src="{{ asset('images/IMG_0639.png') }}" alt="">
                                </span>
                                <span class="timeline-names">
                                    <strong>{{ $entry->title }}</strong>
                                    <small>
                                        {{ $entry->user_name }}・{{ \Carbon\Carbon::parse($entry->created_at)->diffForHumans() }}
                                    </small>
                                </span>
                            </div>

                            <span class="timeline-count">{{ $entry->value }}{{ $entry->unit }}</span>
                        </div>

                        @if ($entry->memo)
                            <p class="timeline-memo">{{ $entry->memo }}</p>
                        @endif

                        <a href="{{ route('situation.show', $entry->goal_id) }}" class="timeline-link">
                            詳細を見る ›
                        </a>
                    </div>
                </article>
            @empty
                <div class="card empty-card">
                    <img src="{{ asset('images/IMG_0639.png') }}" alt="">
                    <p>まだ記録がありません</p>
                    <a href="{{ route('current-goals') }}" class="btn-primary">目標を見る</a>
                </div>
            @endforelse
        </div>

    </main>
@endsection
