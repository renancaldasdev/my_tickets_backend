<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TicketInteractionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/', [TicketController::class, 'index']);
    Route::post('/', [TicketController::class, 'store']);
    Route::get('/{uuid}', [TicketController::class, 'show']);

    Route::middleware(['role:manager|agent|dev'])->group(function () {
        Route::put('/{uuid}', [TicketController::class, 'update']);
        Route::patch('/{uuid}/assign', [TicketController::class, 'assign']);
        Route::patch('/{uuid}/start-progress', [TicketController::class, 'startProgress']);
        Route::patch('/{uuid}/resolve', [TicketController::class, 'resolve']);
        Route::patch('/{uuid}/reopen', [TicketController::class, 'reopen']);
    });

    Route::middleware(['role:manager|dev'])->group(function () {
        Route::patch('/{uuid}/close', [TicketController::class, 'close']);
    });

    Route::prefix('/{uuid}/interactions')->group(function () {
        Route::get('/', [TicketInteractionController::class, 'index']);
        Route::post('/', [TicketInteractionController::class, 'store']);
    });
});
