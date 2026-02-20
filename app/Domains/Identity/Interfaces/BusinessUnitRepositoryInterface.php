<?php

declare(strict_types=1);

namespace App\Domains\Identity\Interfaces;

use App\Domains\Identity\Models\BusinessUnit;

interface BusinessUnitRepositoryInterface
{
    public function create(array $data): BusinessUnit;
}
