<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{uuid}', [UserController::class, 'show']);
    Route::put('/{uuid}', [UserController::class, 'update']);
    Route::patch('/{uuid}/deactivate', [UserController::class, 'deactivate']);
});
