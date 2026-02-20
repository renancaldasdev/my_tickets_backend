<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\User;
use Illuminate\Validation\ValidationException;

class ResendVerificationEmailService
{
    public function handle(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'message' => ['Este e-mail já foi verificado.'],
            ]);
        }

        $user->sendEmailVerificationNotification();
    }
}
