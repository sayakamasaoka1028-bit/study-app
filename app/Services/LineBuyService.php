<?php

namespace App\Services;

use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LineBuyService
{
    /**
     * 「買ってきます」が押された時の処理（仮）
     */
    public static function handleBuy(Item $item, User $user): void
    {
        // 🔒 ガード：LINE未連携なら何もしない
        if (!$user->line_user_id) {
            Log::info('LINE未連携ユーザーの操作をスキップ', [
                'user_id' => $user->id,
            ]);
            return;
        }

        // 🧪 ここに後でロジックを書く
        Log::info('LineBuyService handleBuy reached', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }
}
