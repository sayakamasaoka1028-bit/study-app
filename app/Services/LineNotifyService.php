<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class LineNotifyService
{
    /**
     * 🔴 在庫切れ通知（家族全員に一斉送信・ボタンなし）
     */
    public static function sendToAll(string $message): void
    {
        $token = config('services.line.channel_access_token');
        if (!$token) return;

        // 家族全員の line_user_id を取得
        $userIds = User::whereNotNull('line_user_id')
            ->pluck('line_user_id')
            ->unique()
            ->values()
            ->toArray();

        if (count($userIds) === 0) return;

        $response = Http::withHeaders([
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

        logger()->info('LINE multicast debug', [
            'count'  => count($userIds),
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
    }

    /**
     * ✅ 個別メッセージ送信（誰かが「買ってきます」押した後など）
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

        logger()->info('LINE push debug', [
            'to'     => $lineUserId,
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
    }
}
