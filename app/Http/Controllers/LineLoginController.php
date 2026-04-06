<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // まずは取得できているか確認
        dd($lineUser);
    }
}


