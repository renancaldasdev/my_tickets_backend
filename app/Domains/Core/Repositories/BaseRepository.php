<?php

declare(strict_types=1);

namespace App\Domains\Core\Repositories;

use App\Domains\Core\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @implements BaseRepositoryInterface<TModel>
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var class-string<Model>
     */
    protected string $model;

    public function create(array $data): Model
    {
        /** @var TModel $instance */
        $instance = $this->model::create($data);

        return $instance;
    }

    /**
     * @param  TModel  $model
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model;
    }

    /**
     * @param  TModel  $model
     */
    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    /**
     * @return Collection<int, TModel>
     */
    public function all(): Collection
    {
        /** @var Collection<int, TModel> $results */
        $results = $this->query()->get();

        return $results;
    }

    /**
     * @param  array<string, mixed>  $criteria
     * @return Collection<int, TModel>
     */
    public function allWhere(array $criteria): Collection
    {
        /** @var Collection<int, TModel> $results */
        $results = $this->query()->where($criteria)->get();

        return $results;
    }

    /**
     * @return TModel
     */
    public function find(int $id): Model
    {
        /** @var TModel $result */
        $result = $this->query()->findOrFail($id);

        return $result;
    }

    /**
     * @return TModel
     */
    public function findByUuid(string $uuid): Model
    {
        /** @var TModel $result */
        $result = $this->query()->where('uuid', $uuid)->firstOrFail();

        return $result;
    }

    protected function query(): Builder
    {
        return $this->model::query();
    }
}
