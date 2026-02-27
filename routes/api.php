<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'OK'], 200);
});
Route::prefix('auth')
    ->group(base_path('routes/auth.php'));

Route::prefix('business-units')
    ->group(base_path('routes/businessUnit.php'));

Route::prefix('categories')
    ->group(base_path('routes/category.php'));

Route::prefix('users')
    ->group(base_path('routes/user.php'));
