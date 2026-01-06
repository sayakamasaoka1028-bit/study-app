<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class LineNotifyService
{
    /**
     * 🔴 在庫切れ通知（ボタン付き）
     */
    public static function sendOutOfStock(string $itemName, int $itemId): void
    {
        $token = config('services.line.channel_access_token');
        if (!$token) return;

        $users = User::whereNotNull('line_user_id')->get();

        foreach ($users as $user) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->post('https://api.line.me/v2/bot/message/push', [
                'to' => $user->line_user_id,
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => "⚠️ 在庫切れ\n{$itemName}\n⏰ " . now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'type' => 'template',
                        'altText' => '在庫切れ通知',
                        'template' => [
                            'type' => 'buttons',
                            'title' => '在庫切れ',
                            'text' => "{$itemName} が無くなりました",
                            'actions' => [
                                [
                                    'type' => 'uri',
                                    'label' => '🛒 買ってきます',
                                    'uri' => config('app.url')
                                        . "/buy/{$itemId}/yes?line_user_id={$user->line_user_id}",
                                ],
                                [
                                    'type' => 'uri',
                                    'label' => '🙅‍♀️ 買ってきません',
                                    'uri' => config('app.url')
                                        . "/buy/{$itemId}/no?line_user_id={$user->line_user_id}",
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            logger()->info('LINE push debug (out_of_stock)', [
                'user_id' => $user->id,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
        }
    }

    /**
     * ✅ 汎用メッセージ送信（買ってきます後の通知用）
     */
    public static function send(string $message, string $lineUserId): void
    {
        $token = config('services.line.channel_access_token');
        if (!$token) return;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push', [
            'to' => $lineUserId,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message,
                ],
            ],
        ]);

        logger()->info('LINE push debug (send)', [
            'to'     => $lineUserId,
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
    }
}
