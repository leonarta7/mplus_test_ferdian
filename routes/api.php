<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

Route::prefix('/v1')->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

        Route::get('/google', [AuthController::class, 'googleCallback']);
        Route::get('/facebook', [AuthController::class, 'facebookCallback']);

        Route::middleware('auth:api')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/token/refresh', [AuthController::class, 'refresh']);
        });
    });

    Route::prefix('/user')->middleware('auth:api')->group(function () {
        Route::get('/profile', [UserController::class, 'info']);
        Route::put('/profile', [UserController::class, 'update']);
    });
});
