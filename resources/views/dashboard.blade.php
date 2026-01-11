@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-4">📊 ダッシュボード</h1>

    <p>ログイン中：{{ auth()->user()->name }}</p>

    <hr class="my-4">

    <h3>📱 LINE連携状態</h3>

    @if(auth()->user()->line_user_id)
        <p class="text-green-600">✅ LINE連携済み（通知可能）</p>
    @else
        <p class="text-red-600">⚠️ LINE未連携</p>
        <a href="{{ route('line.login') }}" class="text-blue-600 underline">
            LINE連携する
        </a>
    @endif
@endsection
