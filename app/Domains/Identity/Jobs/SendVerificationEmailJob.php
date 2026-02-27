<?php

declare(strict_types=1);

namespace App\Domains\Identity\Jobs;

use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVerificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user
    ) {}

    public function handle(): void
    {
        event(new Registered($this->user));
    }
}
