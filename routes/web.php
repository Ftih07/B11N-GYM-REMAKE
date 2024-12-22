<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;


Route::get('/', function () {
    return view('index');
});


Route::get('/product', function () {
    return view('product');
});

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/facilities', [FacilitiesController::class, 'index'])->name('facilities.index');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');

Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product', [ProductController::class, 'product'])->name('product.index');

Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [ProductController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/update/{id}', [ProductController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove/{id}', [ProductController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update/{id}', [ProductController::class, 'updateQuantity'])->name('cart.update');

Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/b11n-gym-store', [ProductController::class, 'showB11NStore'])->name('b11n.store');
Route::get('/k1ng-gym-store', [ProductController::class, 'showKingStore'])->name('king.store');