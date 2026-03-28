<?php

declare(strict_types=1);

namespace App\Domains\Support\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Support\Interfaces\TicketInteractionRepositoryInterface;
use App\Domains\Support\Models\TicketInteraction;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<TicketInteraction>
 */
class TicketInteractionRepository extends BaseRepository implements TicketInteractionRepositoryInterface
{
    protected string $model = TicketInteraction::class;

    /**
     * @return Collection<int, TicketInteraction>
     */
    public function allByTicket(int $ticketId): Collection
    {
        /** @var Collection<int, TicketInteraction> $results */
        $results = $this->model::query()
            ->where('ticket_id', $ticketId)
            ->with(['user'])
            ->oldest()
            ->get();

        return $results;
    }

    /**
     * @return Collection<int, TicketInteraction>
     */
    public function publicByTicket(int $ticketId): Collection
    {
        /** @var Collection<int, TicketInteraction> $results */
        $results = $this->model::query()
            ->where('ticket_id', $ticketId)
            ->where('is_internal', false)
            ->with(['user'])
            ->oldest()
            ->get();

        return $results;
    }
}
