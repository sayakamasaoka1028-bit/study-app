<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LineWebhookController extends Controller
{
    public function handle(Request $request)
    {
        logger()->info('LINE webhook raw', $request->all());

        $events = $request->input('events', []);

        foreach ($events as $event) {

            $lineUserId = data_get($event, 'source.userId');
            if (!$lineUserId) {
                continue;
            }

            // ① すでに登録済みなら何もしない
            if (User::where('line_user_id', $lineUserId)->exists()) {
                continue;
            }

            // ② 直近で更新されたユーザーに紐づけ
            $user = User::orderBy('updated_at', 'desc')->first();

            if ($user) {
                $user->line_user_id = $lineUserId;
                $user->save();

                logger()->info('LINE user linked automatically', [
                    'user_id' => $user->id,
                    'line_user_id' => $lineUserId,
                ]);
            }
        }

        return response('OK', 200);
    }
}

