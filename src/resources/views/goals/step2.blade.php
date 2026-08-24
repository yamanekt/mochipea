@extends('layouts.flow')
@section('title', '目標作成')
@section('content')
<h1 class="flow-question">何を目標に<br>する？</h1><p class="flow-help">具体的なことほど続きます</p><form action="{{ route('goals.new.save', ['step' => 2]) }}" method="post">@csrf<div class="flow-input"><input type="text" name="title" maxlength="30" value="{{ old('title', $draft['title'] ?? '') }}" placeholder="例：英単語を覚える" autofocus required></div><button type="submit" class="btn-primary flow-submit">次へ</button></form>
@endsection

