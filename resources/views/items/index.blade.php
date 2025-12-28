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
            <td>{{ $item->name }}</td>

            <td>
                @if ($item->quantity <= 1)
                    <span style="color:red; font-weight:bold;">
                        {{ $item->quantity }}
                    </span>
                @else
                    {{ $item->quantity }}
                @endif
            </td>

            <td>
                {{-- 使った --}}
                <form method="POST"
                      action="{{ route('items.use', $item) }}"
                      style="display:inline;">
                    @csrf
                    <button type="submit">使った</button>
                </form>
               {{-- 在庫を増やす --}}
                <form method="POST"
                      action="{{ route('items.add', $item) }}"
                      style="display:inline;">
                    @csrf
                    <button type="submit">＋</button>
                </form>


                {{-- 削除 --}}
                <form method="POST"
                      action="{{ route('items.destroy', $item) }}"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('削除しますか？')">
                        削除
                    </button>
                </form>
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
