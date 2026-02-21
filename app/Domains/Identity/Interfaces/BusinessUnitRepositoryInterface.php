<?php

declare(strict_types=1);

namespace App\Domains\Identity\Interfaces;

use App\Domains\Identity\Models\BusinessUnit;
use Illuminate\Support\Collection;

interface BusinessUnitRepositoryInterface
{
    public function create(array $data): BusinessUnit;

    public function getByCustomerId(int $customerId): Collection;

    public function findBySlugAndCustomerId(string $slug, int $customerId): BusinessUnit;

    public function update(BusinessUnit $businessUnit, array $data): BusinessUnit;
}
