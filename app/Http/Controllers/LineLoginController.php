<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LineLoginController extends Controller
{
    public function redirect()
    {
        return 'ここからLINEログインに飛ばす予定';
    }

    public function callback()
    {
        return 'LINEから戻ってきた予定';
    }
}
