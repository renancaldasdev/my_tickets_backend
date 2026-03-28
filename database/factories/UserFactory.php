<?php

namespace Database\Factories;

use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\Customer;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            'role' => 'user',

            'business_unit_id' => BusinessUnit::factory(),
            'customer_id' => Customer::factory(),
        ];
    }

    public function manager(): static   // ✅ renomeado de admin() para manager()
    {
        return $this->state(['role' => 'manager']);
    }

    public function agent(): static
    {
        return $this->state(['role' => 'agent']);
    }

    public function dev(): static
    {
        return $this->state(['role' => 'dev', 'customer_id' => null]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
