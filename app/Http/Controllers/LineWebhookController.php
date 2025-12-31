<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LineWebhookController extends Controller
{
    public function handle(Request $request)
    {
        logger()->info('LINE webhook raw', $request->all());

        $event = $request->input('events.0');
        if (!$event) {
            return response('OK', 200);
        }

        $lineUserId = data_get($event, 'source.userId');
        if (!$lineUserId) {
            return response('OK', 200);
        }

        // ★ ここが超重要
        // example1111@gmail.com のユーザーに固定で紐づける
        User::where('email', 'example1111@gmail.com')->update([
            'line_user_id' => $lineUserId,
        ]);

        logger()->info('LINE user linked', [
            'email' => 'example1111@gmail.com',
            'line_user_id' => $lineUserId,
        ]);

        return response('OK', 200);
    }
}
