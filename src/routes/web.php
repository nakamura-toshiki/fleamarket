<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'showItem'])->name('show');

Route::post('register', [CustomRegisteredUserController::class, 'store'])->name('register');

Route::middleware('auth')->group(function (){
    Route::get('/mypage', [ItemController::class, 'showMypage'])->name('mypage');
    Route::get('/mypage/profile', [ItemController::class, 'editProfile'])->name('edit');
    Route::post('/mypage/profile', [ItemController::class, 'update'])->name('update');
    Route::get('/purchase/address/{item_id}', [ItemController::class, 'editAddress'])->name('address');
    Route::post('/purchase/address/{item_id}', [ItemController::class, 'updateAddress'])->name('newAddress');
    Route::get('/sell', [ItemController::class, 'sellItem'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('store');
    Route::post('/item/{item_id}', [ItemController::class, 'comment'])->name('comment');
    Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('like');

    Route::get('/purchase/{item_id}', [StripeController::class, 'order'])->name('order');
    Route::post('/purchase/{item_id}', [StripeController::class, 'storeOrder'])->name('storeOrder');
    Route::post('/checkout/{item_id}', [StripeController::class, 'checkout'])->name('checkout');
    Route::get('/success/{item_id}', [StripeController::class, 'success'])->name('success');
    Route::get('/cancel/{item_id}', [StripeController::class, 'cancel'])->name('cancel');
});


