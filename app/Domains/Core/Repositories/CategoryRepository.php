<?php

declare(strict_types=1);

namespace App\Domains\Core\Repositories;

use App\Domains\Core\Interfaces\CategoryRepositoryInterface;
use App\Domains\Core\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected string $model = Category::class;
}
