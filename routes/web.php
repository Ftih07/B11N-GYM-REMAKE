<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    return view('index');
});


Route::get('/product', function () {
    return view('product');
});

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/product', [HomeController::class, 'product'])->name('product');