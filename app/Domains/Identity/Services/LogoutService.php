<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\User;

class LogoutService
{
    public function handle(User $user): void
    {
        $user->currentAccessToken()->delete();

        $user->tokens()->delete();
    }
}
