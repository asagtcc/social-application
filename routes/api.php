<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\SocialAccountController;
use App\Http\Controllers\SocialNetworkController;



Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('sendOpt', [AuthController::class, 'sendOpt']);
    Route::post('verifyOtp', [AuthController::class, 'verifyOtp']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::prefix('profile')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [UserController::class, 'profile']);
            Route::put('update', [UserController::class, 'update']);
            Route::delete('delete', [UserController::class, 'delete']);
        });

    Route::prefix('social-auth')->group(function () {
        Route::get('{network}/redirect', [SocialAuthController::class, 'redirect']);
        Route::get('{network}/callback', [SocialAuthController::class, 'callback']);
    });
    Route::resource('social-networks', SocialNetworkController::class);
    Route::resource('social-accounts', SocialAccountController::class);
});


