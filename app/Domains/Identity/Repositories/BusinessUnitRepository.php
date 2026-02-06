<?php

declare(strict_types=1);

namespace App\Domains\Identity\Repositories;

use App\Domains\Identity\Interfaces\BusinessUnitRepositoryInterface;
use App\Domains\Identity\Models\BusinessUnit;

class BusinessUnitRepository implements BusinessUnitRepositoryInterface
{
    protected $model = BusinessUnit::class;
}
