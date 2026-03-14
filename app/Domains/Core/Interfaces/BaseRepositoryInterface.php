<?php

declare(strict_types=1);

namespace App\Domains\Core\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
interface BaseRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $data
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
     * @param  TModel  $model
     */
    public function delete(Model $model): bool;

    /**
     * @return Collection<int, TModel>
     */
    public function all(): Collection;

    /**
     * @param  array<string, mixed>  $criteria
     * @return Collection<int, TModel>
     */
    public function allWhere(array $criteria): Collection;

    /**
     * @return TModel
     */
    public function find(int $id): Model;

    /**
     * @return TModel
     */
    public function findByUuid(string $uuid): Model;
}
