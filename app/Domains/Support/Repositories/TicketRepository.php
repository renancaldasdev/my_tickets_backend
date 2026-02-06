<?php

declare(strict_types=1);

namespace App\Domains\Support\Repositories;

use App\Domains\Support\Interfaces\TicketRepositoryInterface;
use App\Domains\Support\Models\Ticket;

class TicketRepository implements TicketRepositoryInterface
{
    protected $model = Ticket::class;
}
