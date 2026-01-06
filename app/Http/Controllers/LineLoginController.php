<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LineLoginController extends Controller
{
    /**
     * LINEログインへリダイレクト
     */
    public function redirect()
    {
        // ★ stateless を使わない（state を正しく使う）
        return Socialite::driver('line')
            ->scopes(['openid', 'profile'])
            ->redirect();
    }

    /**
     * LINEログインのコールバック
     */
    public function callback()
    {
        // ★ stateful のまま受け取る
        $lineUser = Socialite::driver('line')->user();

        $lineId = $lineUser->getId();

        if (!$lineId) {
            abort(500, 'LINEユーザーIDが取得できませんでした');
        }

        // すでにログイン中のユーザーに紐づけ
        $user = Auth::user();

        if (!$user) {
            abort(403, 'ログイン中のユーザーがいません');
        }

        $user->line_user_id = $lineId;
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'LINE連携が完了しました');
    }
}
