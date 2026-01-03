<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class LineLoginController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('line')->redirect();
    }

    public function callback()
    {
        $lineUser = Socialite::driver('line')->stateless()->user();
        $lineId   = $lineUser->getId();
        $email    = 'line_' . $lineId . '@example.com';

        // ① line_user_id で探す
        $user = User::where('line_user_id', $lineId)->first();

        // ② 見つからなければ email で探す（過去の残骸対策）
        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        // ③ それでもなければ新規作成
        if (!$user) {
            $user = User::create([
                'name' => $lineUser->getName() ?? 'LINEユーザー',
                'email' => $email,
                'password' => bcrypt(Str::random(32)),
                'line_user_id' => $lineId,
            ]);
        } else {
            // ④ 既存ユーザーに LINE ID を紐付け
            if (!$user->line_user_id) {
                $user->update(['line_user_id' => $lineId]);
            }
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
