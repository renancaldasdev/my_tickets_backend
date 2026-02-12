<?php

declare(strict_types=1);

namespace App\Domains\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template T of \Illuminate\Database\Eloquent\Model
 */
abstract class BaseRepository
{
    /**
     * @var class-string<Model>
     */
    protected $model;

    protected function applyTenantScope(): Builder
    {
        $instance = new $this->model;
        $query = $instance->newQuery();

        $user = auth()->user();

        if ($user && $user->role === 'dev') {
            return $query;
        }

        return $query->where('customer_id', $user?->customer_id);
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
