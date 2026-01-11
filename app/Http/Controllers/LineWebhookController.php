<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LineWebhookController extends Controller
{
    public function handle(Request $request)
    {
        logger()->info('WEBHOOK RAW', $request->all());

        $events = $request->input('events', []);

        foreach ($events as $event) {
            $lineUserId = data_get($event, 'source.userId');

            if (!$lineUserId) {
                continue;
            }

            // ★ 仮：パパを email で特定して紐づける
            User::where('email', 'papa@example.com')
                ->update(['line_user_id' => $lineUserId]);

            logger()->info('LINE USER LINKED', [
                'email' => 'papa@example.com',
                'line_user_id' => $lineUserId,
            ]);
        }

        return response('OK', 200);
    }
}
