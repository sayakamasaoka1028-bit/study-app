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
     */
    public function yes(Item $item, Request $request)
    {
        return $this->accept($item, $request);
    }

    /**
     * 🙅‍♀️ 「買ってきません」
     */
    public function no(Item $item)
    {
        // LINE用：画面遷移しない
        return response('ok');
    }

    /**
     * ✅ 確定処理（LINE前提）
     */
    public function accept(Item $item, Request $request)
    {
        // LINEの userId
        $lineUserId = $request->query('line_user_id');

        $buyer = User::where('line_user_id', $lineUserId)->first();
        $buyerName = $buyer?->nickname ?? $buyer?->name ?? '誰か';

        // purchases に記録
        Purchase::create([
            'item_id' => $item->id,
            'status' => 'accepted',
            'accepted_by' => $buyer?->id,
            'last_notified_at' => now(),
        ]);

        // LINE通知（全員）
        $users = User::whereNotNull('line_user_id')->get();
        foreach ($users as $user) {
            LineNotifyService::send(
                "🛒 {$item->name} は「{$buyerName}」が買ってきます",
                $user->line_user_id
            );
        }

        // 🔴 ここが重要：リダイレクトしない
        return response('OK');
    }
}
