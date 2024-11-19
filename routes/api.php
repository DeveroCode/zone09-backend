<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::controller(ReservationController::class)->group(function () {
        Route::get('/reservations', 'index');
        Route::post('/update-reservation/{id}', 'update');
        Route::post('/reservation-loan-update/{id}', 'loanUpdate');
        Route::delete('/delete-reservation/{id}', 'destroy');
        Route::get('/search-reservation/{word}', 'search');
        Route::get('/stats', 'show');
    });

    // Route::get('/user', [UserController::class, 'show']);
    Route::controller(UserController::class)->group(function () {
        Route::post('/create-user', 'store');
        Route::get('/all-users', 'index');
        Route::post('/update-user/{id}', 'update');
        Route::post('/update-password/{id}', 'updatePassword');
        Route::delete('/delete-user/{id}', 'destroy');
        Route::get('/search-users/{word}', 'show');
    })->middleware(IsAdmin::class);
});

Route::controller(ReservationController::class)->group(function () {
    Route::get('/available-computers/{start}/{end}', 'getComputersAvailable');
    Route::post('/create-reservation', 'store');
    Route::get('/computers', 'computers');
});
Route::post('/auth/user', [AuthController::class, 'login']);