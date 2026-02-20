<?php

declare(strict_types=1);

namespace App\Domains\Identity\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\BusinessUnit;

class BusinessUnitRepository extends BaseRepository implements BusinessUnitRepositoryInterface
{
    protected $model = BusinessUnit::class;

    public function create(array $data): BusinessUnit
    {
        /** @var BusinessUnit $businessUnit */
        $businessUnit = $this->model::create($data);

        return $businessUnit;
    }
}
