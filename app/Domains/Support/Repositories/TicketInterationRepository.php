<?php

declare(strict_types=1);

namespace App\Domains\Support\Repositories;

use App\Domains\Support\Interfaces\TicketInterationRepositoryInterface;
use App\Domains\Support\Models\TicketInteraction;

class TicketInterationRepository implements TicketInterationRepositoryInterface
{
    protected $model = TicketInteraction::class;
}
