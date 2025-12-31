<?php

namespace App\Http\Controllers;

use App\Models\Item;

class BuyController extends Controller
{
    public function yes(Item $item)
    {
        // とりあえず動作確認用
        return "🛒 {$item->name} を買ってきます！";
    }

    public function no(Item $item)
    {
        return "🙅‍♀️ {$item->name} は買いません";
    }
}
