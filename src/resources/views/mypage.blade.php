@extends('layouts.app')
@section('title', 'マイページ')
@push('css') @vite(['resources/css/mypage.css']) @endpush
@section('content')
<div class="page-bg"></div><main class="page mypage"><p class="eyebrow">マイページ</p><div class="mypage-head"><div class="mypage-stats"><div class="mypage-stat"><strong>12<small>件</small></strong><span>登録した目標</span></div><div class="mypage-stat"><strong>68<small>%</small></strong><span>勝率</span></div></div><img src="{{ asset('images/IMG_0641.png') }}" alt="" class="mypage-avatar"></div><section class="card account-card"><h2>アカウント</h2><div class="account-row">ユーザー名 <span>{{ auth()->user()->name }}　›</span></div><div class="account-row">メールアドレス <span>{{ auth()->user()->email }}　›</span></div><div class="account-row">パスワード <b>変更する　›</b></div></section><a href="#" class="account-link">ログアウト <span>›</span></a><a href="#" class="account-link account-link-danger">アカウントを削除 <span>›</span></a></main>
@endsection

