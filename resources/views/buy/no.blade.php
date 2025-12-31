@extends('layouts.app')

@section('content')
<h1>🙅‍♀️ 今回は買いません</h1>

<p>{{ $item->name }} は今回は買いません。</p>

<a href="{{ route('items.index') }}">在庫一覧に戻る</a>
@endsection
