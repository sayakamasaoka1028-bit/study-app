<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LinePushService
{
    public static function push(string $to, string $message): void
    {
        Http::withToken(config('services.line.channel_access_token'))
            ->post('https://api.line.me/v2/bot/message/push', [
                'to' => $to,
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $message,
                    ],
                ],
            ]);
    }
}
