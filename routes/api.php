<?php

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
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

// Public API routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);

// API routes requiring auth
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::patch('/change-password', [UserController::class, 'changePassword']);
    Route::patch('/update-info', [UserController::class, 'updateInfo']);
    Route::delete('/delete-account', [UserController::class, 'delete']);

    // API routes for products
    Route::prefix('/products')->group(function () {
        Route::get('/{page?}/{sort?}', [ProductController::class, 'list']);

        // API routes for admins
        Route::middleware('admin.access')->group(function () {
            Route::post('/', [ProductController::class, 'create']);
            Route::patch('/{product}', [ProductController::class, 'update']);
            Route::delete('/{product}', [ProductController::class, 'delete']);
        });
    });

    // API routes for orders
    Route::prefix('/orders')->group(function () {
        Route::post('/', [OrderController::class, 'checkout']);
    });
});
