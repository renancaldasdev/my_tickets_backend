<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::post('/', [CategoryController::class, 'store']);
});
