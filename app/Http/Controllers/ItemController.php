<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Services\LineNotifyService;

class ItemController extends Controller
{
    // 📦 一覧表示
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // ➕ 新規追加
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        Item::create([
            'name'     => $request->name,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('items.index');
    }

// 🔴 在庫を減らす
public function decrease(Item $item)
{
    logger()->info('LOGIN USER', [
        'id' => auth()->id(),
        'email' => auth()->user()->email ?? null,
    ]);

    if ($item->quantity > 0) {

        $before = $item->quantity;
        $item->decrement('quantity');

        if ($before === 1) {
            \App\Services\LineNotifyService::sendOutOfStock(
                $item->name,
                $item->id
            );
        }
    }

    return redirect()->route('items.index');
}


    // 🟢 在庫を増やす
    public function increase(Item $item)
    {
        $item->increment('quantity');
        return redirect()->route('items.index');
    }

    // 🗑 削除
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index');
    }
}

