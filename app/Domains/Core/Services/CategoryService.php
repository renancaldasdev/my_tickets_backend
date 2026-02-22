<?php

declare(strict_types=1);

namespace App\Domains\Core\Services;

use App\Domains\Core\Interfaces\CategoryRepositoryInterface;
use App\Domains\Core\Models\Category;
use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected BusinessUnitRepositoryInterface $businessUnitRepository
    ) {}

    public function listCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

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

    public function getCategory(string $uuid): Category
    {
        /** @var Category $category */
        $category = $this->categoryRepository->findByUuid($uuid);

        return $category;
    }

    public function updateCategory(string $uuid, array $data, User $manager)
    {
        /** @var Category $category */
        $category = $this->categoryRepository->findByUuid($uuid);

        /** @var Category $updatedCategory */
        $updatedCategory = $this->categoryRepository->update($category, $data);

        return $updatedCategory;
    }

    public function deactivateCategory(string $uuid): Category
    {
        /** @var Category $category */
        $category = $this->categoryRepository->findByUuid($uuid);

        /** @var Category $deactivatedCategory */
        $deactivatedCategory = $this->categoryRepository->update($category, [
            'is_active' => false,
        ]);

        return $deactivatedCategory;
    }
}
