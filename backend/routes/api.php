<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\ProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'login']);
Route::get('verify-token', [LoginController::class, 'verifyToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::post('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});
