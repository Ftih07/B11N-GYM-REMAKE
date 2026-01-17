<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;

use App\Http\Controllers\Gym\BiinGymController;
use App\Http\Controllers\Gym\KingGymController;
use App\Http\Controllers\Gym\PaymentController;

use App\Http\Controllers\Kost\KostController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\PrintController;
use App\Http\Controllers\Store\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('home'); //Home - Landing Page
Route::get('/biin-gym', [BiinGymController::class, 'index'])->name('gym.biin'); //B11N Gym
Route::get('/king-gym', [KingGymController::class, 'index'])->name('gym.king'); // K1NG Gym
Route::get('/kost-istana-merdeka-3', [KostController::class, 'index'])->name('kost'); // Kost Istana Merdeka

// Blog Route
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/{id}', [BlogController::class, 'show'])->name('blogs.show');
});

//Store Route
Route::get('/biin-king-gym-store', [StoreController::class, 'showBiinKingGymStore'])->name('store.biin-king');
Route::get('/biin-gym-store', [StoreController::class, 'showBiinStore'])->name('store.biin');
Route::get('/king-gym-store', [StoreController::class, 'showKingStore'])->name('store.king');

//Product Route
Route::get('product/{id}', [ProductController::class, 'show'])->name('store.product.show');

//Cart Route
Route::get('/cart', [CartController::class, 'index'])->name('cart.view');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

//Checkout Route
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

//Booking Kost Route
Route::post('/kost-istana-merdeka-3/book', [KostController::class, 'store'])->name('kost.book');

//Membership Payment Route
Route::post('/payment/upload', [PaymentController::class, 'uploadPayment']);

Route::get('/print/transaction/{id}', [PrintController::class, 'printStruk'])->name('print.struk');