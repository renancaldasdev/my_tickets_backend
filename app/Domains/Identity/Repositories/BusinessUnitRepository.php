<?php

declare(strict_types=1);

namespace App\Domains\Identity\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\BusinessUnit;
use Illuminate\Support\Collection;

class BusinessUnitRepository extends BaseRepository implements BusinessUnitRepositoryInterface
{
    protected $model = BusinessUnit::class;

    public function create(array $data): BusinessUnit
    {
        /** @var BusinessUnit $businessUnit */
        $businessUnit = $this->model::create($data);

        return $businessUnit;
    }

    public function getByCustomerId(int $customerId): Collection
    {
        return $this->model::where('customer_id', $customerId)->get();
    }

    public function findBySlugAndCustomerId(string $slug, int $customerId): BusinessUnit
    {
        /** @var BusinessUnit $businessUnit */
        $businessUnit = $this->model::where('slug', $slug)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        return $businessUnit;
    }

    public function update(BusinessUnit $businessUnit, array $data): BusinessUnit
    {
        $businessUnit->update($data);

        return $businessUnit;
    }
}
