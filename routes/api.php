<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/password-saver-api')
    ->group(function() {
        Route::post('register', [\App\Modules\PasswordSaverApi\Auth\Controllers\AuthController::class, 'register']);
        Route::post('login', [\App\Modules\PasswordSaverApi\Auth\Controllers\AuthController::class, 'login']);
    });

Route::prefix('v1/password-saver-api')
    ->middleware(['auth:sanctum'])
    ->group(function() {
        Route::get('logout', [\App\Modules\PasswordSaverApi\Auth\Controllers\AuthController::class, 'logout']);
        Route::get('user', [\App\Modules\PasswordSaverApi\User\Controllers\UserController::class, 'info']);
        Route::put('user/setPinCode', [\App\Modules\PasswordSaverApi\User\Controllers\UserController::class, 'setPinCode']);
        Route::put('user/updatePinCode', [\App\Modules\PasswordSaverApi\User\Controllers\UserController::class, 'updatePinCode']);

        Route::post('categories', [\App\Modules\PasswordSaverApi\Category\Controllers\CategoryAccountController::class, 'store']);
        Route::put('categories/{categoryAccount}', [\App\Modules\PasswordSaverApi\Category\Controllers\CategoryAccountController::class, 'update']);
        Route::delete('categories/{categoryAccount}', [\App\Modules\PasswordSaverApi\Category\Controllers\CategoryAccountController::class, 'destroy']);
        Route::get('categories/{categoryAccount}', [\App\Modules\PasswordSaverApi\Category\Controllers\CategoryAccountController::class, 'show']);
        Route::get('categories', [\App\Modules\PasswordSaverApi\Category\Controllers\CategoryAccountController::class, 'index']);
    });
