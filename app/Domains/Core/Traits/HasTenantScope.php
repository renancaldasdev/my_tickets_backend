<?php

declare(strict_types=1);

namespace App\Domains\Core\Traits;

use App\Domains\Core\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Builder;

trait HasTenantScope
{
    public static function bootHasTenantScope(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    /**
     * Retorna uma query sem o filtro de tenant.
     * Útil para operações administrativas ou de role 'dev'.
     *
     * @return Builder<static>
     */
    public static function withoutTenantScope(): Builder
    {
        /** @var Builder<static> */
        return static::withoutGlobalScope(TenantScope::class);
    }
}
