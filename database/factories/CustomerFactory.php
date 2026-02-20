<?php

namespace Database\Factories;

use App\Domains\Identity\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * @var class-string<Customer>
     */
    protected $model = Customer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company;

        return [
            'name' => $name,
            'email' => $this->faker->unique()->companyEmail,
            'domain' => Str::slug($name).'.tickets.com',
            'document' => (string) rand(10000000000000, 99999999999999),
            'logo' => null,
        ];
    }
}
