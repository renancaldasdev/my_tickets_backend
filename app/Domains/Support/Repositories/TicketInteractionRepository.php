<?php

declare(strict_types=1);

namespace App\Domains\Support\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Support\Interfaces\TicketInteractionRepositoryInterface;
use App\Domains\Support\Models\TicketInteraction;

/**
 * @extends BaseRepository<TicketInteraction>
 */
class TicketInteractionRepository extends BaseRepository implements TicketInteractionRepositoryInterface
{
    protected string $model = TicketInteraction::class;
}
