<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::as('frontend.')->group(function(){
    Route::get('/',[HomeController::class, 'index'])->name('home');
    Route::get('{slug}~{id}',[ProductController::class, 'detail'])->name('product.detail');

    // Shopping cart
    Route::post('add-cart',[CartController::class, 'add'])->name('cart.add');
    Route::post('remove-cart', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('update-cart', [CartController::class, 'update'])->name('cart.update');
});