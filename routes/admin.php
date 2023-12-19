<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;

Route::prefix('admin')->group(function(){
    // localhost:8000/admin/login
    Route::get('login', [LoginController::class, 'index']);

    // localhost:8000/admin/dashboard
    Route::get('dashboard', function(){
        return "Admin - Dashboard";
    });
});