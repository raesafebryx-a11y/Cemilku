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
Route::post('/login', [AuthController::class, 'login']);

// Resource Publik (Menampilkan Produk & Kategori tanpa login)
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/categories/{category}', [CategorieController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// ==========================================
// 2. PROTECTED ROUTES (Wajib Bearer Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth & User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Kelola Kategori & Produk (Hanya Admin / User Login yang bisa tambah, edit, hapus)
    Route::apiResource('categories', CategorieController::class)->except(['index', 'show']);
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

});
