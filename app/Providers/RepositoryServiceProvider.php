<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Core\Interfaces\CategoryRepositoryInterface;
use App\Domains\Core\Interfaces\PriorityRepositoryInterface;
use App\Domains\Core\Interfaces\StatusRepositoryInterface;
use App\Domains\Core\Repositories\CategoryRepository;
use App\Domains\Core\Repositories\PriorityRepository;
use App\Domains\Core\Repositories\StatusRepository;
use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Interfaces\UserRepositoryInterface;
use App\Domains\Identity\Repositories\BusinessUnitRepository;
use App\Domains\Identity\Repositories\UserRepository;
use App\Domains\Support\Interfaces\TicketInterationRepositoryInterface;
use App\Domains\Support\Interfaces\TicketRepositoryInterface;
use App\Domains\Support\Repositories\TicketInterationRepository;
use App\Domains\Support\Repositories\TicketRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BusinessUnitRepositoryInterface::class, BusinessUnitRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(TicketInterationRepositoryInterface::class, TicketInterationRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(PriorityRepositoryInterface::class, PriorityRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }
}
