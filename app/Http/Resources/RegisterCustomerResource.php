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
        // 1. Resolvemos o Customer e avisamos ao PHPStan quem ele é
        /** @var Customer|null $customer */
        $customer = $this->customer;

        // 2. Agora que o PHPStan sabe que $customer é um Customer,
        // ele aceita o acesso a ->businessUnits
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
