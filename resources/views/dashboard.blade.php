@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 space-y-6 bg-pink-50 rounded-2xl">

  <!-- ヘッダー -->
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-pink-700">🏠 家のダッシュボード</h1>

    <!-- ログアウトボタン -->
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button
        type="submit"
        class="px-4 py-2 bg-gray-500 hover:bg-gray-600
               text-white rounded-lg shadow">
        ログアウト
      </button>
    </form>
  </div>

  <!-- 📦 在庫一覧 -->
  <section class="bg-white rounded-xl shadow p-4 border border-pink-200">
    <h2 class="font-semibold mb-3 text-pink-700">📦 在庫</h2>

    @if ($items->isEmpty())
      <p class="text-sm text-gray-400">在庫はまだありません</p>
    @else
      <ul class="space-y-3">
        @foreach ($items as $item)
          <li class="flex items-center justify-between border rounded-lg p-4 bg-pink-50">

            <!-- 名前と在庫 -->
            <div>
              <div class="font-medium text-gray-800">
                {{ $item->name }}
              </div>

              <div class="text-sm font-semibold {{ $item->quantity == 0 ? 'text-red-600' : 'text-gray-600' }}">
                在庫 {{ $item->quantity }}
              </div>
            </div>

            <!-- 操作ボタン -->
            <div class="flex gap-2">
              <!-- 減らす -->
              <form method="POST" action="{{ route('items.decrease', $item) }}">
                @csrf
                <button
                  class="w-10 h-10 flex items-center justify-center
                         bg-red-400 hover:bg-red-500
                         text-white font-bold rounded-full shadow">
                  −
                </button>
              </form>

              <!-- 増やす -->
              <form method="POST" action="{{ route('items.increase', $item) }}">
                @csrf
                <button
                  class="w-10 h-10 flex items-center justify-center
                         bg-green-500 hover:bg-green-600
                         text-white font-bold rounded-full shadow">
                  ＋
                </button>
              </form>
            </div>

          </li>
        @endforeach
      </ul>
    @endif

    <!-- ➕ 新しい備品を追加 -->
    <form method="POST" action="{{ route('items.store') }}" class="mt-6 flex gap-2">
      @csrf
      <input
        type="text"
        name="name"
        placeholder="備品名"
        required
        class="flex-1 rounded-lg border-gray-300">

      <input
        type="number"
        name="quantity"
        value="1"
        min="0"
        class="w-20 rounded-lg border-gray-300">

      <button
        class="px-4 py-2 bg-pink-500 text-white rounded-lg shadow">
        追加
      </button>
    </form>

  </section>

</div>
@endsection
