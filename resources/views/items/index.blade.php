@extends('layouts.app')

@section('content')

<h1 class="text-xl font-bold mb-4">📦 備品一覧</h1>

@if ($items->isEmpty())
    <p>まだ備品が登録されていません。</p>
@else
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>備品名</th>
            <th>在庫</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <!-- 備品名 -->
            <td>{{ $item->name }}</td>

            <!-- 在庫数 -->
            <td>
                @if ($item->quantity <= 1)
                    <span style="color:red; font-weight:bold;">
                        {{ $item->quantity }}
                    </span>
                @else
                    {{ $item->quantity }}
                @endif
            </td>

            <!-- 操作（管理者のみ） -->
            <td>
                @can('admin')
                <div class="item-actions">
                    <!-- 減らす -->
                    <form method="POST" action="{{ route('items.decrease', $item) }}">
                        @csrf
                        <button class="btn use">使った</button>
                    </form>

                    <!-- 増やす -->
                    <form method="POST" action="{{ route('items.increase', $item) }}">
                        @csrf
                        <button class="btn add">＋ 追加</button>
                    </form>

                    <!-- 削除 -->
                    <form method="POST"
                          action="{{ route('items.destroy', $item) }}"
                          onsubmit="return confirm('削除していい？')">
                        @csrf
                        @method('DELETE')
                        <button class="btn delete">削除</button>
                    </form>
                </div>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<hr class="my-4">

<h2>＋ 備品を追加</h2>

<form method="POST" action="{{ route('items.store') }}">
    @csrf
    <input type="text" name="name" placeholder="備品名" required>
    <input type="number" name="quantity" placeholder="数量" min="0" required>
    <button type="submit">追加</button>
</form>

@endsection
