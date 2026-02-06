<?php

declare(strict_types=1);

namespace App\Domains\Core\Repositories;

use App\Domains\Core\Interfaces\StatusRepositoryInterface;
use App\Domains\Core\Models\Status;

class StatusRepository implements StatusRepositoryInterface
{
    protected $model = Status::class;
}
