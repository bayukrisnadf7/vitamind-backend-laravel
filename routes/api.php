<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\UserDetailController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user/detail', [UserDetailController::class, 'show']);
    Route::post('/user/detail', [UserDetailController::class, 'store']);
    Route::put('/user/detail', [UserDetailController::class, 'store']);
    Route::get('/screenings', [ScreeningController::class, 'index']);
    Route::post('/screenings', [ScreeningController::class, 'store']);
    Route::get('/screenings/{id}', [ScreeningController::class, 'show']);
});

Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/auth/reset-password-otp', [AuthController::class, 'resetPasswordOtp']);

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong',
    ]);
});
