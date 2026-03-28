<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\Identity\Models\User;
use App\Domains\Support\Services\TicketInteractionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketInteractionRequest;
use App\Http\Resources\TicketInteractionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketInteractionController extends Controller
{
    public function __construct(
        protected TicketInteractionService $interactionService
    ) {}

    public function index(Request $request, string $uuid): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $interactions = $this->interactionService->listInteractions($uuid, $user);

        return TicketInteractionResource::collection($interactions);
    }

    public function store(StoreTicketInteractionRequest $request, string $uuid): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $interaction = $this->interactionService->addInteraction(
            $uuid,
            $request->validated(),
            $user
        );

        return response()->json([
            'message' => 'Interação adicionada com sucesso!',
            'data' => new TicketInteractionResource($interaction),
        ], 201);
    }
}
