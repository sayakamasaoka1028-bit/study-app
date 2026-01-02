<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LineLoginController extends Controller
{
    /**
     * LINEログインへリダイレクト
     */
    public function redirect()
    {
        // ⚠️ 今はまだ押さない前提
        return Socialite::driver('line')->redirect();
    }

    /**
     * LINEから戻ってくるコールバック
     */
    public function callback()
    {
        // まだ実装しない（次回）
        return 'LINEから戻ってきた予定';
    }
}
