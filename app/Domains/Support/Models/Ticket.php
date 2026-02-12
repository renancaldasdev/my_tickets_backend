<?php

declare(strict_types=1);

namespace App\Domains\Support\Models;

use App\Domains\Core\Models\Category;
use App\Domains\Core\Models\Priority;
use App\Domains\Core\Models\Status;
use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\Customer;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'description',
        'status_id',
        'priority_id',
        'business_unit_id',
        'category_id',
        'customer_id',
        'user_id',
        'agent_id',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    // Quem abriu o ticket
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quem está resolvendo
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(TicketInteraction::class);
    } //
}
