<?php

declare(strict_types=1);

namespace App\Domains\Support\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Support\Interfaces\TicketInterationRepositoryInterface;
use App\Domains\Support\Models\TicketInteraction;

class TicketInterationRepository extends BaseRepository implements TicketInterationRepositoryInterface
{
    protected $model = TicketInteraction::class;
}
