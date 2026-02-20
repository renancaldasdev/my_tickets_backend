<?php

use App\Http\Controllers\Api\BusinessUnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['role:manager'])->group(function () {
        Route::post('/', [BusinessUnitController::class, 'store']);
    });
});
