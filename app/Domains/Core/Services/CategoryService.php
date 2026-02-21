<?php

declare(strict_types=1);

namespace App\Domains\Core\Services;

use App\Domains\Core\Interfaces\CategoryRepositoryInterface;
use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\User;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected BusinessUnitRepositoryInterface $businessUnitRepository
    ) {}

    public function createCategory(array $data, User $manager)
    {
        $businessUnit = $this->businessUnitRepository->findBySlugAndCustomerId(
            $data['business_unit_slug'],
            $manager->customer_id
        );

        $categoryData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'business_unit_id' => $businessUnit->id,
            'customer_id' => $manager->customer_id,
        ];

        return $this->categoryRepository->create($categoryData);
    }
}
