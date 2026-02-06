<?php

declare(strict_types=1);

namespace App\Domains\Core\Models;

use App\Domains\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    protected $fillable = ['name', 'slug'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
