<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domains\Core\Models\Category;
use App\Domains\Core\Models\Priority;
use App\Domains\Core\Models\Status;
use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\User;
use App\Domains\Support\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ticket
 */
class TicketResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Ticket $ticket */
        $ticket = $this->resource;

        return [
            'id' => $ticket->uuid,
            'subject' => $ticket->subject,
            'description' => $ticket->description,

            'status' => $this->whenLoaded('status', function () use ($ticket) {
                /** @var Status $status */
                $status = $ticket->status;

                return [
                    'name' => $status->name,
                    'slug' => $status->slug,
                ];
            }),

            'priority' => $this->whenLoaded('priority', function () use ($ticket) {
                /** @var Priority $priority */
                $priority = $ticket->priority;

                return [
                    'name' => $priority->name,
                    'slug' => $priority->slug,
                ];
            }),

            'category' => $this->whenLoaded('category', function () use ($ticket) {
                /** @var Category $category */
                $category = $ticket->category;

                return [
                    'name' => $category->name,
                    'uuid' => $category->uuid,
                ];
            }),

            'business_unit' => $this->whenLoaded('businessUnit', function () use ($ticket) {
                /** @var BusinessUnit $businessUnit */
                $businessUnit = $ticket->businessUnit;

                return [
                    'name' => $businessUnit->name,
                    'slug' => $businessUnit->slug,
                ];
            }),

            'creator' => $this->whenLoaded('creator', function () use ($ticket) {
                /** @var User $creator */
                $creator = $ticket->creator;

                return [
                    'id' => $creator->uuid,
                    'name' => $creator->name,
                ];
            }),

            'agent' => $this->whenLoaded('agent', function () use ($ticket) {
                if (! $ticket->agent) {
                    return null;
                }

                /** @var User $agent */
                $agent = $ticket->agent;

                return [
                    'id' => $agent->uuid,
                    'name' => $agent->name,
                ];
            }),

            'interactions_count' => $this->whenLoaded(
                'interactions',
                fn () => $ticket->interactions->count()
            ),

            'resolved_at' => $ticket->resolved_at?->toIso8601String(),
            'created_at' => $ticket->created_at?->toIso8601String(),
            'updated_at' => $ticket->updated_at?->toIso8601String(),
        ];
    }
}
