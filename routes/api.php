<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'OK'], 200);
});
Route::prefix('auth')
    ->group(base_path('routes/auth.php'));
