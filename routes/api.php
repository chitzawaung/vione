<?php

use App\Http\Controllers\API\ProductAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (no authentication required)
Route::prefix('products')->group(function () {
    Route::get('/', [ProductAPIController::class, 'index']);
    Route::get('{product}', [ProductAPIController::class, 'show']);
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    Route::post('/', [ProductAPIController::class, 'store']);
    Route::put('{product}', [ProductAPIController::class, 'update']);
    Route::delete('{product}', [ProductAPIController::class, 'destroy']);
});

// Admin routes (admin role required)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('products/reports')->group(function () {
    Route::get('low-stock', [ProductAPIController::class, 'lowStock']);
    Route::get('out-of-stock', [ProductAPIController::class, 'outOfStock']);
});

// User info route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
