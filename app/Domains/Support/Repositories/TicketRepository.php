<?php

declare(strict_types=1);

namespace App\Domains\Support\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Support\Interfaces\TicketRepositoryInterface;
use App\Domains\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    protected string $model = Ticket::class;

    /**
     * O TenantScope (customer_id) é aplicado automaticamente pelo HasTenantScope
     * do model Ticket. Aqui apenas adicionamos o filtro extra por business_unit_id.
     *
     * @return Collection<int, Ticket>
     */
    public function allByBusinessUnit(int $businessUnitId): Collection
    {
        /** @var Collection<int, Ticket> $results */
        $results = $this->model::query()
            ->where('business_unit_id', $businessUnitId)
            ->with(['status', 'priority', 'category', 'creator', 'agent'])
            ->latest()
            ->get();

        return $results;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function allByAgent(int $agentId): Collection
    {
        /** @var Collection<int, Ticket> $results */
        $results = $this->model::query()
            ->where('agent_id', $agentId)
            ->with(['status', 'priority', 'category', 'businessUnit'])
            ->latest()
            ->get();

        return $results;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function allByUser(int $userId): Collection
    {
        /** @var Collection<int, Ticket> $results */
        $results = $this->model::query()
            ->where('user_id', $userId)
            ->with(['status', 'priority', 'category', 'businessUnit', 'agent'])
            ->latest()
            ->get();

        return $results;
    }

    public function findByUuidWithRelations(string $uuid): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->query()
            ->where('uuid', $uuid)
            ->with([
                'status',
                'priority',
                'category',
                'businessUnit',
                'customer',
                'creator',
                'agent',
                'interactions.user',
            ])
            ->firstOrFail();

        return $ticket;
    }
}
