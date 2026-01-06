<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

            // ① すでにこの LINE ID が使われてたら何もしない
            if (User::where('line_user_id', $lineUserId)->exists()) {
                continue;
            }

            // ② ログイン中ユーザーがいれば、その人に紐づけ
            $user = Auth::user();

            if ($user && !$user->line_user_id) {
                $user->line_user_id = $lineUserId;
                $user->save();

                logger()->info('LINE linked', [
                    'user_id' => $user->id,
                    'line_user_id' => $lineUserId,
                ]);
            }
        }

        return response('OK', 200);
    }
}
