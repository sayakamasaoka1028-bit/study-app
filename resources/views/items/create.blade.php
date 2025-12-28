@extends('layouts.app')

@section('content')

<h1>📦 備品を追加</h1>

<form method="POST" action="/items">
    @csrf

    <div>
        <label>備品名</label>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>初期在庫数</label>
        <input type="number" name="stock" required>
    </div>

    <div>
        <label>最低在庫数</label>
        <input type="number" name="min_stock" required>
    </div>

    <button type="submit">追加する</button>
</form>

<hr>

<a href="/items">← 一覧に戻る</a>

@endsection





