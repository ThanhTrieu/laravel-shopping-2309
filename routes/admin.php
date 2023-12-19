<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->as('admin.')->group(function(){
    // localhost:8000/admin/login
    Route::get('login', [LoginController::class, 'index'])->name('login');
    // handle login
    Route::post('handle-login', [LoginController::class, 'handleLogin'])->name('handle.login');

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->as('admin.')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
