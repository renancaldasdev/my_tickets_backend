<?php

declare(strict_types=1);

namespace App\Domains\Support\Interfaces;

use App\Domains\Core\Interfaces\BaseRepositoryInterface;
use App\Domains\Support\Models\TicketInteraction;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepositoryInterface<TicketInteraction>
 */
interface TicketInteractionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, TicketInteraction>
     */
    public function allByTicket(int $ticketId): Collection;

    /**
     * @return Collection<int, TicketInteraction>
     */
    public function publicByTicket(int $ticketId): Collection;
}
