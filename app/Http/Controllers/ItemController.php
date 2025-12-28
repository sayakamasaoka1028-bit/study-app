<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        Item::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('items.index');
    }

    public function use(Item $item)
    {
        if ($item->quantity > 0) {
            $item->decrement('quantity');
        }

        return redirect()->route('items.index');
    }

    // ➕ 在庫を増やす
    public function add(Item $item)
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
