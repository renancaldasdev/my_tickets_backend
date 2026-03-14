<?php

declare(strict_types=1);

namespace App\Domains\Core\Scopes;

use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Eloquent Global Scope que isola registros por customer_id automaticamente.
 *
 * Aplicar via trait HasTenantScope nos models que possuem a coluna customer_id.
 * O scope é ignorado automaticamente para usuários com role 'dev'.
 */
class TenantScope implements Scope
{
    /**
     * @param  Builder<Model>  $builder
     */
    public function apply(Builder $builder, Model $model): void
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            return;
        }

        if ($user->hasRole('dev')) {
            return;
        }

        $builder->where($model->getTable().'.customer_id', $user->customer_id);
    }
}
