<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domains\Identity\Models\User;
use App\Domains\Support\Models\TicketInteraction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TicketInteraction
 */
class TicketInteractionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var TicketInteraction $interaction */
        $interaction = $this->resource;

        return [
            'id' => $interaction->id,
            'content' => $interaction->content,
            'is_internal' => $interaction->is_internal,

            'author' => $this->whenLoaded('user', function () use ($interaction) {
                /** @var User $user */
                $user = $interaction->user;

                return [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'role' => $user->role,
                ];
            }),

            'created_at' => $interaction->created_at?->toIso8601String(),
        ];
    }
}
