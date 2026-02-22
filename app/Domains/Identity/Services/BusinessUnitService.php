<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Collection;
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

    public function listBusinessUnits(): Collection
    {
        return $this->businessUnitRepository->all();
    }

    public function getBusinessUnit(string $slug, User $manager): BusinessUnit
    {
        return $this->businessUnitRepository->findBySlugAndCustomerId($slug, $manager->customer_id);
    }

    public function updateBusinessUnit(string $slug, array $data, User $manager): BusinessUnit
    {
        $businessUnit = $this->businessUnitRepository->findBySlugAndCustomerId($slug, $manager->customer_id);

        if (isset($data['name']) && $data['name'] !== $businessUnit->name) {
            $data['slug'] = Str::slug($data['name'].'-'.$manager->customer_id);
        }

        /** @var BusinessUnit $updated */
        $updated = $this->businessUnitRepository->update($businessUnit, $data);

        return $updated;
    }

    public function deactivateBusinessUnit(string $slug, User $manager): BusinessUnit
    {
        $businessUnit = $this->businessUnitRepository->findBySlugAndCustomerId($slug, $manager->customer_id);

        /** @var BusinessUnit $deactivatedUnit */
        $deactivatedUnit = $this->businessUnitRepository->update($businessUnit, [
            'is_active' => false,
        ]);

        return $deactivatedUnit;
    }
}
