<?php

declare(strict_types=1);

namespace App\Domains\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * @template T of \Illuminate\Database\Eloquent\Model
 */
abstract class BaseRepository
{
    /** @var T */
    protected $model;

    protected function applyTenantScope(): Builder
    {
        $query = $this->model->newQuery();
        $user = Auth::user();

        if ($user && $user->role === 'dev') {
            return $query;
        }

        return $query->where('customer_id', $user->customer_id);
    }

    public function all()
    {
        return $this->applyTenantScope()->get();
    }

    public function find($id)
    {
        return $this->applyTenantScope()->findOrFail($id);
    }
}
