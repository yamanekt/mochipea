@extends('layouts.app')

@section('title', 'タイムライン')

@push('css')
    @vite(['resources/css/goals.css'])
@endpush

@section('content')
  <div class="bg"></div>

  {{-- 案内キャラクター --}}
  <div class="guide-character">
    <img src="{{ asset('images/IMG_0639.png') }}" alt="案内キャラクター">
  </div>

  {{-- 達成入力（コメント）を新しい順に並べたタイムライン --}}
  <div class="goal-list">
    @forelse ($entries as $entry)
      <div class="goal-card">
        {{-- 目標名 --}}
        <p class="goal-title">目標名：{{ $entry->title }}</p>

        {{-- 投稿者名 ・ 何日前か（Carbonのロケールはapp.phpでjaに設定済みなので「3日前」と表示される） --}}
        <p class="entry-meta">
          {{ $entry->user_name }}・{{ \Carbon\Carbon::parse($entry->created_at)->diffForHumans() }}
        </p>

        {{-- 今回の回数（達成数）＋単位 --}}
        <p class="entry-count">回数：{{ $entry->value }}{{ $entry->unit }}</p>

        {{-- コメント（memoが未入力ならNULLなので代わりの文言を出す） --}}
        <p class="entry-comment">
          {{ $entry->memo ?? '（コメントなし）' }}
        </p>

        {{-- その目標の詳細（現在状況）画面へ --}}
        <a href="{{ route('situation.show', $entry->goal_id) }}" class="detail-btn">詳細を見る</a>
      </div>
    @empty
      <div class="goal-card">
        <p>まだ達成入力がありません。</p>
      </div>
    @endforelse
  </div>
@endsection
