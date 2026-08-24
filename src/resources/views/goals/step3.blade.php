@extends('layouts.flow')
@section('title', '目標作成')
@section('content')
@php $unit = old('unit', $draft['unit'] ?? ''); @endphp
<h1 class="flow-question">どれくらい<br>やる？</h1><p class="flow-help">合計の数を入れてください</p><form action="{{ route('goals.new.save', ['step' => 3]) }}" method="post">@csrf<div class="flow-input flow-input-number"><input type="text" name="target_value" inputmode="numeric" pattern="[0-9]*" value="{{ old('target_value', $draft['target_value'] ?? '') }}" placeholder="100" required></div><p class="flow-sublabel">単位</p><div class="chip-group flow-chips">@foreach (\App\Http\Controllers\GoalFlowController::UNITS as $item)<label class="chip"><input type="radio" name="unit" value="{{ $item }}" {{ $unit === $item ? 'checked' : '' }} required>{{ $item }}</label>@endforeach<label class="chip"><input type="radio" name="unit" value="other" {{ !in_array($unit, \App\Http\Controllers\GoalFlowController::UNITS, true) && $unit !== '' ? 'checked' : '' }}>その他</label></div><div class="flow-input flow-custom"><input type="text" name="unit_custom" maxlength="10" placeholder="例：km"></div><button type="submit" class="btn-primary flow-submit">次へ</button></form>
@endsection
