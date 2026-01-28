<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Identity\Interfaces\UserRepositoryInterface;
use App\Domains\Identity\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
