<?php

declare(strict_types=1);

namespace App\Domains\Core\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    /**
     * @return TModel
     */
    public function create(array $data): Model;

    /**
     * @param  TModel  $model
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function update(Model $model, array $data): Model;

    /**
     * @return Collection<int, TModel>
     */
    public function getByCustomerId(int $customerId): Collection;

    /**
     * @return Collection<int, TModel>
     */
    public function all(): Collection;

    /**
     * @param  mixed  $id
     * @return TModel
     */
    public function find($id): Model;
}
