<?php

namespace Database\Seeders;

use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\Customer;
use Illuminate\Database\Seeder;

class BusinessUnitSeeder extends Seeder
{
    protected $model = BusinessUnit::class;

    public function run(): void
    {
        /** @var Customer $customer */
        $customer = Customer::first() ?? Customer::factory()->create([
            'name' => 'Empresa Padrão',
            'domain' => 'default.tickets.com',
        ]);

        $customer->businessUnits()->create([
            'name' => 'Matriz (HQ)',
            'slug' => 'matriz-hq',
            'is_active' => true,
        ]);

        $customer->businessUnits()->create([
            'name' => 'Suporte TI',
            'slug' => 'suporte-ti',
            'is_active' => true,
        ]);
    }
}
