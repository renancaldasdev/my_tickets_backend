<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\Identity\Models\User;
use App\Domains\Support\Services\TicketService;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $tickets = $this->ticketService->listTickets($user);

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->createTicket($request->validated(), $user);

        return response()->json([
            'message' => 'Ticket criado com sucesso!',
            'data' => new TicketResource($ticket),
        ], 201);
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->getTicket($uuid, $user);

        return response()->json([
            'data' => new TicketResource($ticket),
        ]);
    }

    public function update(UpdateTicketRequest $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->updateTicket($uuid, $request->validated(), $user);

        return response()->json([
            'message' => 'Ticket atualizado com sucesso!',
            'data' => new TicketResource($ticket),
        ]);
    }

    public function assign(AssignTicketRequest $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->assignAgent(
            $uuid,
            $request->validated('agent_uuid'),
            $user
        );

        return response()->json([
            'message' => 'Agente atribuído com sucesso!',
            'data' => new TicketResource($ticket),
        ]);
    }

    public function startProgress(Request $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->startProgress($uuid, $user);

        return response()->json([
            'message' => 'Ticket em andamento.',
            'data' => new TicketResource($ticket),
        ]);
    }

    public function resolve(Request $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->resolveTicket($uuid, $user);

        return response()->json([
            'message' => 'Ticket marcado como resolvido.',
            'data' => new TicketResource($ticket),
        ]);
    }

    public function close(Request $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->closeTicket($uuid, $user);

        return response()->json([
            'message' => 'Ticket fechado com sucesso.',
            'data' => new TicketResource($ticket),
        ]);
    }

    public function reopen(Request $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ticket = $this->ticketService->reopenTicket($uuid, $user);

        return response()->json([
            'message' => 'Ticket reaberto com sucesso.',
            'data' => new TicketResource($ticket),
        ]);
    }
}
