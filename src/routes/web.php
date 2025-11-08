<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ItemController::class, 'index'])->name('index');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('detail');

Route::controller(UserController::class)->group(function(){
    Route::get('/register', 'registerForm')->name('registerForm');
    Route::post('/register', 'register')->name('register');
    Route::get('/login', 'loginForm')->name('loginForm');
    Route::post('/login', 'login')->name('login');
});

Route::prefix('email')->name('verification.')->controller(MailController::class)->group(function(){
    // メール認証リンクのクリック処理（認証完了アクション）
    Route::get('/verify/{id}/{hash}', 'verify')->middleware(['signed'])->name('verify');
    // メール認証画面
    Route::get('/verify', 'notice')->name('notice');
    // 認証メール再送信
    Route::post('/verification-notification-guest', 'sendForGuest')->middleware(['throttle:6,1'])->name('send.guest');
    Route::post('/verification-notification', 'send')->middleware(['auth', 'throttle:6,1'])->name('send');
});

Route::middleware(['auth'])->group(function () {
    // いいね。コメント処理
    Route::prefix('item/{item_id}')->group(function(){
        Route::post('/like', [LikeController::class, 'store'])->name('like');
        Route::delete('/unlike', [LikeController::class, 'destroy'])->name('unlike');
        Route::post('/comment', [CommentController::class, 'store'])->name('comment');
    });

    Route::prefix('mypage')->name('mypage.')->controller(MypageController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
    });

    Route::prefix('sell')->controller(ItemController::class)->group(function () {
        Route::get('/', 'sellForm')->name('sellForm');
        Route::post('/', 'store')->name('sell');
    });

    Route::prefix('purchase')->controller(PurchaseController::class)->group(function () {
        Route::get('/{item_id}', 'index')->name('purchase');
        Route::get('/address/{item_id}', 'edit')->name('address.edit');
        Route::put('/address/{item_id}', 'update')->name('address.update');
    });

    // stripe決済
    Route::prefix('checkout')->controller(StripeController::class)->group(function(){
        Route::post('/', 'checkout')->name('checkout');
        Route::get('/success', 'success')->name('checkout.success');
        Route::get('/cancel', 'cancel')->name('checkout.cancel');
    });

    // 取引チャット
    Route::prefix('transaction')->name('chat.')->controller(ChatController::class)->group(function(){
        Route::get('/{transaction}/chat', 'show')->name('show');
        Route::post('/{transaction}/chat', 'store')->name('store');
        Route::put('/chat/{message}', 'update')->name('update');
        Route::delete('/chat/{message}', 'destroy')->name('destroy');
    });

    // 評価
    Route::post('/transaction/{transaction}/rating', [RatingController::class, 'store'])->name('rating.store');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});