<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\Customer;
use App\Domains\Identity\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class RegisterCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Customer|null $customer */
        $customer = $this->customer;

        /** @var BusinessUnit|null $firstBu */
        $firstBu = $customer?->businessUnits->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'customer' => [
                'name' => $customer->name ?? null,
                'slug' => $firstBu?->slug,
            ],
        ];
    }
}
