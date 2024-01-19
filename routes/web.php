<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;

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
});