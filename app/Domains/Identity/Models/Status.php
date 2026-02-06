<?php

declare(strict_types=1);

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $table = 'status';

    protected $fillable = ['name', 'slug'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
