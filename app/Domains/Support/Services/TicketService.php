<?php

declare(strict_types=1);

namespace App\Domains\Support\Services;

use App\Domains\Core\Interfaces\CategoryRepositoryInterface;
use App\Domains\Core\Interfaces\PriorityRepositoryInterface;
use App\Domains\Core\Interfaces\StatusRepositoryInterface;
use App\Domains\Core\Models\Category;
use App\Domains\Core\Models\Priority;
use App\Domains\Core\Models\Status;
use App\Domains\Identity\Interfaces\UserRepositoryInterface;
use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\User;
use App\Domains\Support\Interfaces\TicketRepositoryInterface;
use App\Domains\Support\Models\Ticket;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private StatusRepositoryInterface $statusRepository,
        private PriorityRepositoryInterface $priorityRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @return Collection<int, Ticket>
     */
    public function listTickets(User $user): Collection
    {
        if ($user->hasRole(['dev', 'manager'])) {
            /** @var Collection<int, Ticket> $tickets */
            $tickets = $this->ticketRepository->all();

            return $tickets;
        }

        if ($user->hasRole('agent')) {
            /** @var Collection<int, Ticket> $tickets */
            $tickets = $this->ticketRepository->allByAgent($user->id);

            return $tickets;
        }

        /** @var Collection<int, Ticket> $tickets */
        $tickets = $this->ticketRepository->allByUser($user->id);

        return $tickets;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createTicket(array $data, User $user): Ticket
    {
        return DB::transaction(function () use ($data, $user) {
            $statusId = $this->resolveStatusId('open');
            $priorityId = $this->resolvePriorityId($data['priority_slug']);
            $categoryId = $this->resolveCategoryId($data['category_uuid']);
            $buId = $this->resolveBusinessUnitId($data['business_unit_slug'], $user->customer_id);

            /** @var Ticket $ticket */
            $ticket = $this->ticketRepository->create([
                'uuid' => Str::uuid(),
                'subject' => $data['subject'],
                'description' => $data['description'],
                'status_id' => $statusId,
                'priority_id' => $priorityId,
                'category_id' => $categoryId,
                'business_unit_id' => $buId,
                'customer_id' => $user->customer_id,
                'user_id' => $user->id,
                'agent_id' => null,
            ]);

            return $ticket;
        });
    }

    public function getTicket(string $uuid, User $user): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($uuid);

        $this->authorizeView($user, $ticket);

        return $ticket;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateTicket(string $uuid, array $data, User $user): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($uuid);

        $this->authorizeStatusChange($user, $ticket);
        $this->ensureNotClosed($ticket);

        $payload = array_filter([
            'subject' => $data['subject'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['priority_slug'])) {
            $payload['priority_id'] = $this->resolvePriorityId($data['priority_slug']);
        }

        if (isset($data['category_uuid'])) {
            $payload['category_id'] = $this->resolveCategoryId($data['category_uuid']);
        }

        /** @var Ticket $updated */
        $updated = $this->ticketRepository->update($ticket, $payload);

        return $updated;
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function assignAgent(string $uuid, string $agentuuid, User $user): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($uuid);
        $agent_id = $this->resolveAgentId($agentuuid);

        $this->authorizeAssign($user, $ticket, $agent_id);
        $this->ensureNotClosed($ticket);

        /** @var Ticket $updated */
        $updated = $this->ticketRepository->update($ticket, ['agent_id' => $agent_id]);

        return $updated;
    }

    public function startProgress(string $uuid, User $user): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($uuid);

        $this->authorizeStatusChange($user, $ticket);

        if (is_null($ticket->agent_id)) {
            throw ValidationException::withMessages([
                'agent_id' => ['Atribua um agente antes de iniciar o progresso.'],
            ]);
        }

        $this->ensureStatusTransition($ticket, current: 'open', next: 'in_progress');

        return $this->applyStatus($ticket, 'in_progress');
    }

    public function resolveTicket(string $uuid, User $user): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($uuid);

        $this->authorizeStatusChange($user, $ticket);
        $this->ensureStatusTransition($ticket, current: 'in_progress', next: 'resolved');

        /** @var Ticket $updated */
        $updated = $this->ticketRepository->update($ticket, [
            'status_id' => $this->resolveStatusId('resolved'),
            'resolved_at' => now(),
        ]);

        return $updated;
    }

    public function closeTicket(string $uuid, User $user): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($uuid);

        $this->authorizeStatusChange($user, $ticket);
        $this->ensureStatusTransition($ticket, current: 'resolved', next: 'closed');

        return $this->applyStatus($ticket, 'closed');
    }

    public function reopenTicket(string $uuid, User $user): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->findByUuidWithRelations($uuid);

        $this->authorizeStatusChange($user, $ticket);

        /** @var Status $status */
        $status = $ticket->status;
        $currentSlug = $status->slug;

        if (! in_array($currentSlug, ['resolved', 'closed'])) {
            throw ValidationException::withMessages([
                'status' => ["Apenas tickets resolvidos ou fechados podem ser reabertos. Status atual: {$currentSlug}."],
            ]);
        }

        /** @var Ticket $updated */
        $updated = $this->ticketRepository->update($ticket, [
            'status_id' => $this->resolveStatusId('open'),
            'agent_id' => null,
            'resolved_at' => null,
        ]);

        return $updated;
    }

    private function resolveStatusId(string $slug): int
    {
        /** @var Status $status */
        $status = $this->statusRepository
            ->allWhere(['slug' => $slug])
            ->firstOrFail();

        return $status->id;
    }

    private function resolveAgentId(string $uuid): int
    {
        /** @var User $agent */
        $agent = $this->userRepository
            ->allWhere(['uuid' => $uuid])
            ->firstOrFail();

        return $agent->id;
    }

    private function resolvePriorityId(string $slug): int
    {
        /** @var Priority $priority */
        $priority = $this->priorityRepository
            ->allWhere(['slug' => $slug])
            ->firstOrFail();

        return $priority->id;
    }

    private function resolveCategoryId(string $uuid): int
    {
        /** @var Category $category */
        $category = $this->categoryRepository
            ->allWhere(['uuid' => $uuid])
            ->firstOrFail();

        return $category->id;
    }

    private function resolveBusinessUnitId(string $slug, int $customerId): int
    {
        return BusinessUnit::where('slug', $slug)
            ->where('customer_id', $customerId)
            ->firstOrFail()
            ->id;
    }

    private function applyStatus(Ticket $ticket, string $slug): Ticket
    {
        /** @var Ticket $updated */
        $updated = $this->ticketRepository->update($ticket, [
            'status_id' => $this->resolveStatusId($slug),
        ]);

        return $updated;
    }

    private function ensureStatusTransition(Ticket $ticket, string $current, string $next): void
    {
        /** @var Status $status */
        $status = $ticket->status;

        if ($status->slug !== $current) {
            throw ValidationException::withMessages([
                'status' => ["Para ir para '{$next}' o ticket deve estar '{$current}'. Status atual: {$status->slug}."],
            ]);
        }
    }

    private function ensureNotClosed(Ticket $ticket): void
    {
        /** @var Status $status */
        $status = $ticket->status;

        if ($status->slug === 'closed') {
            throw ValidationException::withMessages([
                'status' => ['Este ticket está fechado e não pode ser alterado.'],
            ]);
        }
    }

    private function authorizeView(User $user, Ticket $ticket): void
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
            throw new AuthorizationException('Você só pode visualizar seus próprios tickets.');
        }
    }

    private function authorizeStatusChange(User $user, Ticket $ticket): void
    {
        if ($user->customer_id !== $ticket->customer_id) {
            throw new AuthorizationException('Acesso negado.');
        }

        if (! $user->hasRole(['dev', 'manager', 'agent'])) {
            throw new AuthorizationException('Sem permissão para alterar status do ticket.');
        }
    }

    private function authorizeAssign(User $user, Ticket $ticket, int $agentId): void
    {
        if ($user->customer_id !== $ticket->customer_id) {
            throw new AuthorizationException('Acesso negado.');
        }

        if ($user->hasRole('agent')) {
            if ($user->id !== $agentId) {
                throw new AuthorizationException('Agentes só podem se auto-atribuir.');
            }

            return;
        }

        if ($user->hasRole('manager')) {
            $agentExists = User::where('id', $agentId)
                ->where('customer_id', $user->customer_id)
                ->where('role', 'agent')
                ->exists();

            if (! $agentExists) {
                throw ValidationException::withMessages([
                    'agent_id' => ['Agente inválido ou não pertence à sua empresa.'],
                ]);
            }

            return;
        }

        throw new AuthorizationException('Sem permissão para atribuir agentes.');
    }
}
