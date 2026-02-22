<?php

declare(strict_types=1);

namespace App\Domains\Core\Repositories;

use App\Domains\Core\Interfaces\PriorityRepositoryInterface;
use App\Domains\Core\Models\Priority;

class PriorityRepository extends BaseRepository implements PriorityRepositoryInterface
{
    protected string $model = Priority::class;
}
