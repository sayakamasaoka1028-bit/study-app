<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\LineNotifyService;

class BuyController extends Controller
{
    /**
     * 🛒 「買ってきます」（LINEボタン）
     * ※ Webhook 経由なので line_user_id は request から直接取る
     */
    public function yes(Item $item, Request $request)
    {
        // ★ LINE Webhook から来た userId（唯一の正解）
        $lineUserId = $request->input('line_user_id');

        if (!$lineUserId) {
            logger()->warning('LINE userId not found');
            return response('NG', 400);
        }

        // 押した本人
        $buyer = User::where('line_user_id', $lineUserId)->first();

        if (!$buyer) {
            logger()->warning('User not linked', ['line_user_id' => $lineUserId]);
            return response('NG', 400);
        }

        // purchases に記録
        Purchase::create([
            'item_id' => $item->id,
            'status' => 'accepted',
            'accepted_by' => $buyer->id,
            'last_notified_at' => now(),
        ]);

        // 全員に LINE 通知
        LineNotifyService::sendToAll(
            "🛒 {$buyer->name} が\n「{$item->name}」を買います！"
        );

        return response('OK');
    }

    /**
     * 🙅‍♀️ 買わない（今は何もしない）
     */
    public function no(Item $item)
    {
        return response('OK');
    }
}

