<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\SocialAccountController;
use App\Http\Controllers\SocialNetworkController;



Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    // Route::post('register', [AuthController::class, 'register']);
    Route::post('sendOpt', [AuthController::class, 'sendOpt']);
    Route::post('verifyOtp', [AuthController::class, 'verifyOtp']);
    Route::post('set-password/{token}', [AuthController::class, 'setPassword']);
});


Route::group(['middleware' => ['auth:sanctum','IsAdmin']], function () {
    Route::post('organizations/{organizations}/add-user', [OrganizationController::class, 'addUser']);
    Route::delete('organizations/{organizations}/remove-user/{user}', [OrganizationController::class, 'removeUser']);
    Route::apiResource('organizations', OrganizationController::class);
    Route::apiResource('social-networks', SocialNetworkController::class);

  });  

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('profile')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [UserController::class, 'profile']);
        Route::PATCH('update', [UserController::class, 'update']);
        Route::delete('delete', [UserController::class, 'delete']);
    });


    Route::prefix('social-auth')->group(function () {
        Route::get('{network}/redirect', [SocialAuthController::class, 'redirect']);
        Route::get('callback/{network}', [SocialAuthController::class, 'callback']);
    });


    Route::apiResource('social-accounts', SocialAccountController::class);

});


