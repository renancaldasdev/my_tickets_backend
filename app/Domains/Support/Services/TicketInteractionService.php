<?php

declare(strict_types=1);

namespace App\Domains\Support\Services;

use App\Domains\Core\Models\Status;
use App\Domains\Identity\Models\User;
use App\Domains\Support\Interfaces\TicketInteractionRepositoryInterface;
use App\Domains\Support\Interfaces\TicketRepositoryInterface;
use App\Domains\Support\Models\Ticket;
use App\Domains\Support\Models\TicketInteraction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class TicketInteractionService
{
    public function __construct(
        protected TicketInteractionRepositoryInterface $interactionRepository,
        protected TicketRepositoryInterface $ticketRepository,
    ) {}

    /**
     * @return Collection<int, TicketInteraction>
     */
    public function listInteractions(string $ticketUuid, User $user): Collection
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($ticketUuid);

        $this->authorizeAccess($user, $ticket);

        if ($user->hasRole(['dev', 'manager', 'agent'])) {
            return $this->interactionRepository->allByTicket($ticket->id);
        }

        return $this->interactionRepository->publicByTicket($ticket->id);
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function addInteraction(
        string $ticketUuid,
        array $data,
        User $user
    ): TicketInteraction {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($ticketUuid);

        $this->authorizeAccess($user, $ticket);

        /** @var Status $status */
        $status = $ticket->status;

        if ($status->slug === 'closed') {
            throw ValidationException::withMessages([
                'ticket' => ['Não é possível adicionar interações em um ticket fechado.'],
            ]);
        }

        $isInternal = (bool) ($data['is_internal'] ?? false);

        if ($isInternal && ! $user->hasRole(['dev', 'manager', 'agent'])) {
            throw new AuthorizationException('Sem permissão para criar notas internas.');
        }

        /** @var TicketInteraction $interaction */
        $interaction = $this->interactionRepository->create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'content' => $data['content'],
            'is_internal' => $isInternal,
        ]);

        $interaction->load('user');

        return $interaction;
    }

    /**
     * @throws AuthorizationException
     */
    private function authorizeAccess(User $user, Ticket $ticket): void
    {
        if ($user->customer_id !== $ticket->customer_id) {
            throw new AuthorizationException('Acesso negado a este ticket.');
        }

        if ($user->hasRole(['dev', 'manager'])) {
            return;
        }

        if ($user->hasRole('agent')) {
            if (! $user->businessUnits->contains('id', $ticket->business_unit_id)) {
                throw new AuthorizationException('Agente não pertence à BU deste ticket.');
            }

            return;
        }

        if ($user->id !== $ticket->user_id) {
            throw new AuthorizationException('Você não tem acesso a este ticket.');
        }
    }
}
