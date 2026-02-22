<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{uuid}', [CategoryController::class, 'show']);
    Route::put('/{uuid}', [CategoryController::class, 'update']);
    Route::patch('/{uuid}/deactivate', [CategoryController::class, 'deactivate']);
});
