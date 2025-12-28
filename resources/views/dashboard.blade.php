@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 space-y-6 bg-pink-50 rounded-2xl">

  <h1 class="text-2xl font-bold text-pink-700">🏠 家のダッシュボード</h1>

  <!-- 📦 在庫一覧 -->
  <section class="bg-white rounded-xl shadow p-4 border border-pink-200">
    <h2 class="font-semibold mb-3 text-pink-700">📦 在庫</h2>

    @if ($items->isEmpty())
      <p class="text-sm text-gray-400">在庫はまだありません</p>
    @else
      <ul class="space-y-2">
        @foreach ($items as $item)
          <li class="flex items-center justify-between border rounded-lg p-3 bg-pink-50">
            <span class="font-medium">
              {{ $item->name }}
              <span class="text-sm text-gray-500 ml-2">
                在庫 {{ $item->quantity }}
              </span>
            </span>
          </li>
        @endforeach
      </ul>
    @endif
  </section>

</div>
@endsection
