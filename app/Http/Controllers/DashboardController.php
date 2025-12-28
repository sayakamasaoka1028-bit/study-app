<?php

namespace App\Http\Controllers;

use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        // 在庫一覧
        $items = Item::all();

        return view('dashboard', compact('items'));
    }
}
