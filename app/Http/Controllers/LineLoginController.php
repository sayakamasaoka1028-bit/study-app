<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class LineLoginController extends Controller
{
    /**
     * LINEログインへリダイレクト
     */
    public function redirect()
    {
        return Socialite::driver('line')->redirect();
    }

    /**
     * LINEから戻ってくるコールバック
     */
    public function callback()
    {
$lineUser = Socialite::driver('line')->user();
        return response()->json([
            'line_id' => $lineUser->getId(),
            'name'    => $lineUser->getName(),
        ]);
    }
}
