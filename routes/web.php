<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStoreController;

Route::get('/', function () {
    return view('index');
});


Route::get('/product', function () {
    return view('product');
});

Route::get('/', [HomeController::class, 'home'])->name('home'); //Multicore
Route::get('/biin-gym', [HomeController::class, 'index'])->name('index'); //B11N Gym
Route::get('/king-gym', [HomeController::class, 'kinggym'])->name('kinggym'); // K1NG Gym
Route::get('/kost-istana-merdeka-3', [HomeController::class, 'kost'])->name('kost'); // Kost Istana Merdeka


// Blog Route
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index'); 
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show'); 


//Store Product Route
Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product', [ProductController::class, 'product'])->name('product.index');

Route::get('/b11n-gym-store', [ProductController::class, 'showB11NStore'])->name('b11n.store');
Route::get('/k1ng-gym-store', [ProductController::class, 'showKingStore'])->name('king.store');


//Cart Route
Route::post('/cart/add/{id}', [ProductController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [ProductController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/update/{id}', [ProductController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove/{id}', [ProductController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update/{id}', [ProductController::class, 'updateQuantity'])->name('cart.update');

//Checkout Route
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/b11n-gym', [BlogController::class, 'b11nGymBlogs'])->name('blogs.b11n');
Route::get('/blogs/k1ng-gym', [BlogController::class, 'k1ngGymBlogs'])->name('blogs.k1ng');


Route::post('/kost-istana-merdeka-3/book', [HomeController::class, 'bookKost'])->name('kost.book');

Route::post('/payment/upload', [PaymentController::class, 'uploadPayment']);

