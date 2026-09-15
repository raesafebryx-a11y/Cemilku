<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

// ==========================================
// 1. PUBLIC ROUTES (Tanpa Login)
// ==========================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Resource Publik (Kategori & Produk)
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/categories/{category}', [CategorieController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Route Order (Mendukung /orders DAN /order agar tidak 404 di Thunder Client)
Route::post('/orders', [OrderController::class, 'store']);
Route::post('/order', [OrderController::class, 'store']);

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/order', [OrderController::class, 'index']);

Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/order/{id}', [OrderController::class, 'show']);

// ==========================================
// 2. PROTECTED ROUTES (Wajib Bearer Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth & User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Kelola Kategori & Produk (Khusus Admin / Auth User)
    Route::apiResource('categories', CategorieController::class)->except(['index', 'show']);
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);

    // Update Status Order
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::patch('/order/{id}/status', [OrderController::class, 'updateStatus']);

});
