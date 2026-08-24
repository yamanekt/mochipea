@extends('layouts.flow')
@section('title', '目標作成')
@section('content')
<h1 class="flow-question">いつまでに<br>達成する？</h1><p class="flow-help">期限があると続きやすくなります</p><form action="{{ route('goals.new.save', ['step' => 4]) }}" method="post">@csrf<div class="flow-input"><input type="date" name="deadline" value="{{ old('deadline', $draft['deadline'] ?? '') }}" min="{{ now()->toDateString() }}" required></div><button type="submit" class="btn-primary flow-submit">次へ</button></form>
@endsection

