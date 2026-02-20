<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Str;

class BusinessUnitService
{
    public function __construct(
        protected BusinessUnitRepositoryInterface $businessUnitRepository
    ) {}

    public function createBusinessUnit(array $data, User $manager)
    {
        $data['customer_id'] = $manager->customer_id;

        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name'].'-'.$manager->customer_id);
        }

        return $this->businessUnitRepository->create($data);
    }
}
