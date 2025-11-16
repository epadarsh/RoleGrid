<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

// --- Public/Auth Routes ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);


    Route::middleware('admin')->prefix('admin')->group(function () {
        // User Management (Admin CRUD)
        Route::apiResource('users', AdminUserController::class)->only(['index', 'store', 'update', 'destroy']);
        // Product Management (Internal CRUD)
        Route::apiResource('products', AdminProductController::class);
    });
});
