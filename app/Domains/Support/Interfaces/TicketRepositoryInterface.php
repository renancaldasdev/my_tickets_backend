<?php

declare(strict_types=1);

namespace App\Domains\Support\Interfaces;

use App\Domains\Core\Interfaces\BaseRepositoryInterface;
use App\Domains\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
    /** @return Collection<int, Ticket> */
    public function allByBusinessUnit(int $businessUnitId): Collection;

    /** @return Collection<int, Ticket> */
    public function allByAgent(int $agentId): Collection;

    /**
     * @return Collection<int, Ticket>
     */
    public function allByUser(int $userId): Collection;

    /**
     * Eager load das relações mais usadas para evitar N+1.
     */
    public function findByUuidWithRelations(string $uuid): Ticket;
}
