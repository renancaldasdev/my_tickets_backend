<?php

declare(strict_types=1);

namespace App\Domains\Identity\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\BusinessUnit;

class BusinessUnitRepository extends BaseRepository implements BusinessUnitRepositoryInterface
{
    protected string $model = BusinessUnit::class;

    public function findBySlugAndCustomerId(string $slug, int $customerId): BusinessUnit
    {
        /** @var BusinessUnit $businessUnit */
        $businessUnit = $this->model::where('slug', $slug)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        return $businessUnit;
    }
}
