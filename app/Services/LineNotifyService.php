<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class LineNotifyService
{
    /**
     * 🔴 テキスト通知（家族全員）
     */
    public static function sendToAll(string $message): void
    {
        $token = config('services.line.channel_access_token');
        if (!$token) return;

        $userIds = User::whereNotNull('line_user_id')
            ->pluck('line_user_id')
            ->unique()
            ->values()
            ->toArray();

        if (count($userIds) === 0) return;

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/multicast', [
            'to' => $userIds,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message,
                ],
            ],
        ]);
    }

    /**
     * 🔴 在庫切れ通知（LINE → Web遷移）
     */
    public static function sendOutOfStock(string $itemName, int $itemId): void
    {
        $token = config('services.line.channel_access_token');
        if (!$token) return;

        $userIds = User::whereNotNull('line_user_id')
            ->pluck('line_user_id')
            ->unique()
            ->values()
            ->toArray();

        if (count($userIds) === 0) return;

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/multicast', [
            'to' => $userIds,
            'messages' => [
                [
                    'type' => 'template',
                    'altText' => '在庫切れ通知',
                    'template' => [
                        'type' => 'buttons',
                        'text' => "📦 在庫切れ\n{$itemName}\n誰か買ってきますか？",
                        'actions' => [
                            [
                                'type'  => 'uri',
                                'label' => '🛒 買ってきます',
                                'uri'   => url("/items/{$itemId}/buy"),
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
