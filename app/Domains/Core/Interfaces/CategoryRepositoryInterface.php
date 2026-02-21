<?php

declare(strict_types=1);

namespace App\Domains\Core\Interfaces;

use App\Domains\Core\Models\Category;

interface CategoryRepositoryInterface
{
    public function create(array $data): Category;
}
