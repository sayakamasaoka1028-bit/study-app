<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LineLoginController extends Controller
{
    // LINEログイン開始
    public function redirect()
    {
        return Socialite::driver('line')->redirect();
    }

    // LINEログイン後コールバック
    public function callback()
    {
        $lineUser = Socialite::driver('line')->user();

        // email でユーザー取得
        $user = auth()->user();

        if (!$user) {
            abort(403, 'ユーザーが存在しません');
        }

        // LINE userId 保存
        $user->update([
            'line_user_id' => $lineUser->id,
        ]);

        // ログイン状態にする
        Auth::login($user);

        return redirect('/');
    }
}
