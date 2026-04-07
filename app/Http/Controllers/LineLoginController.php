<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LineLoginController extends Controller
{
    // LINEログインへリダイレクト
    public function redirect()
    {
        return Socialite::driver('line')->redirect();
    }

    // LINEからのコールバック
    public function callback()
    {
        $lineUser = Socialite::driver('line')->user();

        $user = Auth::user();

        // LINEユーザーIDを保存
        $user->line_user_id = $lineUser->getId();
        $user->save();

        return redirect('/dashboard')->with('success', 'LINE連携完了！');
    }
}
