<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Services\LineNotifyService;

class LineWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $events = $request->input('events', []);

        foreach ($events as $event) {

            // postback 以外は無視
            if (($event['type'] ?? null) !== 'postback') {
                continue;
            }

            $data = data_get($event, 'postback.data');
            $lineUserId = data_get($event, 'source.userId');

            if (!$data || !$lineUserId) {
                continue;
            }

            parse_str($data, $params);

            if (($params['action'] ?? null) !== 'buy') {
                continue;
            }

            $itemId = (int) ($params['item_id'] ?? 0);
            if (!$itemId) {
                continue;
            }

            $item = Item::find($itemId);
            $user = User::where('line_user_id', $lineUserId)->first();

            if (!$item || !$user) {
                continue;
            }

            // すでに誰かが買う宣言してたら何もしない
            if ($item->buyer_id) {
                continue;
            }

            // buyer を保存
            $item->buyer_id = $user->id;
            $item->save();

            $buyerName = $user->nickname ?? $user->name ?? '誰か';

            LineNotifyService::sendToAll(
                "🛒 {$item->name} は「{$buyerName}」が買ってきます 👍"
            );
        }

        return response('OK', 200);
    }
}

