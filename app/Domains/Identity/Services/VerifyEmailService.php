<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Validation\ValidationException;

class VerifyEmailService
{
    public function handle(int $userId, string $hash): void
    {
        /** @var User $user */
        $user = User::findOrFail($userId);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw ValidationException::withMessages([
                'message' => ['Link de verificação inválido ou expirado.'],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }
}
