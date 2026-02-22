<?php

declare(strict_types=1);

namespace App\Domains\Identity\Repositories;

use App\Domains\Core\Repositories\BaseRepository;
use App\Domains\Identity\Interfaces\UserRepositoryInterface;
use App\Domains\Identity\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected string $model = User::class;
}
