<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SellerController;

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

Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

// Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);

    // User
    Route::put('/user/{user}/restore', [UserController::class, 'restore']);
    Route::apiResource('user', UserController::class);

    // Seller
    Route::put('/seller/{seller}/restore', [SellerController::class, 'restore']);
    Route::post('/seller/{seller}/send-daily-sales-report', [SellerController::class, 'sendDailySalesReport']);
    Route::apiResource('seller', SellerController::class);

    // Sale
    Route::put('/sale/{sale}/restore', [SaleController::class, 'restore']);
    Route::apiResource('sale', SaleController::class);
});
