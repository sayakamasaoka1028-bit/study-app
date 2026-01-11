<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use App\Services\LineNotifyService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/inventory', [InventoryController::class, 'index'])
    ->middleware('auth')
    ->name('inventory.index');

Route::middleware('auth')->group(function () {

    // 備品一覧
    Route::get('/items', [ItemController::class, 'index'])
        ->name('items.index');

    // 備品追加
    Route::post('/items', [ItemController::class, 'store'])
        ->name('items.store');

    // 在庫を減らす
    Route::post('/items/{item}/decrease', [ItemController::class, 'decrease'])
        ->name('items.decrease');

    // 在庫を増やす
    Route::post('/items/{item}/increase', [ItemController::class, 'increase'])
        ->name('items.increase');

    // 削除
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])
        ->name('items.destroy');
});
Route::middleware('auth')->get('/items/{item}/buy', function (Item $item) {

    if ($item->buyer_id) {
        return redirect('/items')->with('error', 'すでに購入担当が決まっています');
    }

    $item->buyer_id = auth()->id();
    $item->save();

    LineNotifyService::sendToAll(
        "🛒 購入担当決定！\n\n"
        . auth()->user()->name . " が\n"
        . "『{$item->name}』を\n"
        . "買ってきます 👍"
    );

    return redirect('/items')->with('success', '購入担当になりました！');
});
