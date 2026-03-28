<?php

declare(strict_types=1);

namespace App\Domains\Identity\Models;

use App\Domains\Core\Traits\HasTenantScope;
use App\Domains\Support\Models\Ticket;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;         // <-- OBRIGATÓRIO para @property-read
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $email
 * @property string $role
 * @property int|null $customer_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Customer|null                 $customer
 * @property-read Collection<int, BusinessUnit> $businessUnits
 * @property-read Collection<int, Ticket>       $ticketsCreated
 * @property-read Collection<int, Ticket>       $ticketsAssigned
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, HasTenantScope, Notifiable;

    protected $guard_name = 'api';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'customer_id',
        'role',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return BelongsToMany<BusinessUnit, $this> */
    public function businessUnits(): BelongsToMany
    {
        return $this->belongsToMany(BusinessUnit::class)->withTimestamps();
    }

    /** @return HasMany<Ticket, $this> */
    public function ticketsCreated(): HasMany
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    /** @return HasMany<Ticket, $this> */
    public function ticketsAssigned(): HasMany
    {
        return $this->hasMany(Ticket::class, 'agent_id');
    }
}
