<?php

declare(strict_types=1);

namespace App\Domains\Identity\Interfaces;

use App\Domains\Core\Interfaces\BaseRepositoryInterface;
use App\Domains\Identity\Models\BusinessUnit;

interface BusinessUnitRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlugAndCustomerId(string $slug, int $customerId): BusinessUnit;
}
