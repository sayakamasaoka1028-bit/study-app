@extends('layouts.app')

@section('content')
<h1>🛒 買ってきました！</h1>

<p>{{ $item->name }} を買ってきました。</p>
<p>在庫が 1 増えています。</p>

<a href="{{ route('items.index') }}">在庫一覧に戻る</a>
@endsection

