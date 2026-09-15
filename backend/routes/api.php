<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

// ==========================================
// 1. PUBLIC ROUTES
// Tidak membutuhkan login
// ==========================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Categories
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/categories/{category}', [CategorieController::class, 'show']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);


// ==========================================
// 2. PROTECTED ROUTES
// Membutuhkan Bearer Token Sanctum
// ==========================================

Route::middleware('auth:sanctum')->group(function () {

    // ==========================================
    // AUTH & USER
    // ==========================================

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);


    // ==========================================
    // CATEGORIES
    // Login diperlukan untuk tambah, edit, hapus
    // ==========================================

    Route::apiResource('categories', CategorieController::class)
        ->except(['index', 'show']);


    // ==========================================
    // PRODUCTS
    // Login diperlukan untuk tambah, edit, hapus
    // ==========================================

    Route::apiResource('products', ProductController::class)
        ->except(['index', 'show']);


    // ==========================================
    // ORDERS
    // ==========================================

    Route::get('/orders', [OrderController::class, 'index']);

    Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/orders/{id}', [OrderController::class, 'show']);

    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
});
