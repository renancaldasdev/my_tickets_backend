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
     * @return Builder<TModel>
     */
    protected function applyTenantScope(): Builder
    {
        $query = $this->model::query();

        $user = auth()->user();

        if (! $user) {
            /** @var Builder<TModel> */
            return $query;
        }

        if ($user->role === 'dev') {
            /** @var Builder<TModel> */
            return $query;
        }

        /** @var Builder<TModel> $scopedQuery */
        $scopedQuery = $query->where('customer_id', $user->customer_id);

        return $scopedQuery;
    }

    /**
     * @return Collection<int, TModel>
     */
    public function getByCustomerId(int $customerId): Collection
    {
        /** @var Collection<int, TModel> $collection */
        $collection = $this->model::where('customer_id', $customerId)->get();

        return $collection;
    }

    /**
     * @return Collection<int, TModel>
     */
    public function all(): Collection
    {
        /** @var Collection<int, TModel> $results */
        $results = $this->applyTenantScope()->get();

        return $results;
    }

    public function find($id): Model
    {
        return $this->applyTenantScope()->findOrFail($id);
    }

    /**
     * @return TModel
     */
    public function findByUuid(string $uuid): Model
    {
        /** @var TModel $result */
        $result = $this->applyTenantScope()->where('uuid', $uuid)->firstOrFail();

        return $result;
    }
}
