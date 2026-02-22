<?php

declare(strict_types=1);

namespace App\Domains\Core\Repositories;

use App\Domains\Core\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
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

    protected function applyTenantScope(): Builder
    {
        $query = $this->model::query();

        $user = auth()->user();

        if (! $user) {
            return $query;
        }

        if ($user->role === 'dev') {
            return $query;
        }

        return $query->where('customer_id', $user->customer_id);
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

    public function all(): Collection
    {
        return $this->applyTenantScope()->get();
    }

    public function find($id): Model
    {
        return $this->applyTenantScope()->findOrFail($id);
    }
}
