<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

# Default Laravel API route (optional, can be removed)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

# --- Auth Routes ---
Route::prefix('v1')->group(function () {
    Route::post('register', [App\Http\Controllers\Api\V1\Auth\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Api\V1\Auth\AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [App\Http\Controllers\Api\V1\Auth\AuthController::class, 'logout']);
        Route::get('user', [App\Http\Controllers\Api\V1\Auth\AuthController::class, 'user']);

        # --- Product Management API (Tenant Aware) ---
        Route::apiResource('products', App\Http\Controllers\Api\V1\ProductController::class);

        # --- Order Management API (Tenant Aware) ---
        Route::apiResource('orders', App\Http\Controllers\Api\V1\OrderController::class);
    });
});

# Scribe Documentation Routes
Route::get('/docs', function () {
    return view('scribe.index');
});
