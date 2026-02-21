<?php

use App\Http\Controllers\Api\BusinessUnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['role:manager'])->group(function () {
        Route::get('/', [BusinessUnitController::class, 'index']);
        Route::post('/', [BusinessUnitController::class, 'store']);
        Route::get('/{slug}', [BusinessUnitController::class, 'show']);
        Route::put('/{slug}', [BusinessUnitController::class, 'update']);
        Route::patch('/{slug}/deactivate', [BusinessUnitController::class, 'deactivate']);
    });
});
