<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class LineNotifyService
{
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

                    // ✅ ① 毎回変わるテキスト（LINEキャッシュ対策）
                    [
                        'type' => 'text',
                        'text' => "⚠️ 在庫切れ\n{$itemName}\n⏰ " . now()->format('Y-m-d H:i:s'),
                    ],

                    // ✅ ② ボタン付きメッセージ（復活）
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
                                    'uri' => config('app.url') . "/buy/{$itemId}/yes",
                                ],
                                [
                                    'type' => 'uri',
                                    'label' => '🙅‍♀️ 買ってきません',
                                    'uri' => config('app.url') . "/buy/{$itemId}/no",
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            logger()->info('LINE push debug', [
                'user_id' => $user->id,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
        }
    }
}
