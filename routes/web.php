<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FacilitiesController;

Route::get('/', function () {
    return view('index');
});


Route::get('/product', function () {
    return view('product');
});

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/facilities', [FacilitiesController::class, 'index'])->name('facilities.index');