<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\LineWebhookController;
use App\Http\Controllers\LineLoginController;

Route::get('/', function () {
    return view('welcome');
});

// 認証必須
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/{item}/increase', [ItemController::class, 'increase'])->name('items.increase');
    Route::post('/items/{item}/decrease', [ItemController::class, 'decrease'])->name('items.decrease');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // LINE連携
    Route::post('/line/link', function () {
        return redirect()->away('https://line.me/R/ti/p/@517uosmr');
    })->name('line.link');
});

// 🔴 これが無いと login が消える
require __DIR__.'/auth.php';

// LINE webhook
Route::post('/line/webhook', [LineWebhookController::class, 'handle']);

// 買い物ボタン
Route::get('/buy/{item}/yes', [BuyController::class, 'yes'])->name('buy.yes');
Route::get('/buy/{item}/no',  [BuyController::class, 'no'])->name('buy.no');

//Route::get('/line/login', function () {
   // return 'LINE連携はこれから実装します';
//})->name('line.login');

Route::middleware('auth')->group(function () {
    Route::get('/line/login', [LineLoginController::class, 'redirect'])
        ->name('line.login');
});

Route::get('/line/callback', [LineLoginController::class, 'callback'])
    ->name('line.callback');
