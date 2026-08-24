@extends('layouts.flow')
@section('title', '目標作成')
@section('content')
<h1 class="flow-question">これでいい？</h1><p class="flow-help">決定するとペア相手にも通知されます</p><div class="card summary-card"><div class="row"><span>カテゴリ</span><span>{{ \App\Http\Controllers\GoalFlowController::CATEGORIES[$draft['category']] ?? '' }}</span></div><div class="row"><span>目標名</span><span>{{ $draft['title'] }}</span></div><div class="row"><span>目標値</span><span>{{ $draft['target_value'] }} {{ $draft['unit'] }}</span></div><div class="row"><span>期限</span><span>{{ \Carbon\Carbon::parse($draft['deadline'])->format('Y / m / d') }}</span></div></div><form action="{{ route('goals.new.store') }}" method="post">@csrf<button type="submit" class="btn-primary flow-submit">この目標を決定する</button></form>
@endsection

