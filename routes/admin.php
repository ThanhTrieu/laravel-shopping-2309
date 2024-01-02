<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\CheckAdminLogined;

Route::prefix('admin')->as('admin.')->group(function(){
    // localhost:8000/admin/login
    Route::get('login', [LoginController::class, 'index'])
            ->middleware('is.login.admin')
            ->name('login');
    // handle login
    Route::post('handle-login', [LoginController::class, 'handleLogin'])->name('handle.login');

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->middleware(['check.admin.login'])->as('admin.')->group(function(){
    // tat ca cac routing deu bi middleware "check.admin.login" kiem soat
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
