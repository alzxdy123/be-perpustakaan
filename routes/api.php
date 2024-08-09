<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::get('user', [AuthController::class, 'index']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
    });

    Route::post('profile', [AuthController::class, 'profile'])->middleware('auth:api');
    Route::get('user', [AuthController::class, 'users']);
    Route::apiResource('role', RoleController::class)->except(['show'])->middleware(['auth:api', 'owner']);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('book', BookController::class);
    Route::get('book/home', [ BookController::class,'home']);
    Route::apiResource('borrow', BorrowController::class);
    Route::post('check', [BorrowController::class, 'checkIfBorrowed']);
    Route::post('borrow/admin', [BorrowController::class, 'storeAdmin'])->middleware('owner');

});


