<?php

namespace Database\Seeders;

use App\Domains\Identity\Models\BusinessUnit;
use Illuminate\Database\Seeder;

class BusinessUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessUnit::create([
            'name' => 'Matriz (HQ)',
            'slug' => 'matriz-hq',
            'is_active' => true,
        ]);

        BusinessUnit::create([
            'name' => 'Suporte TI',
            'slug' => 'suporte-ti',
            'is_active' => true,
        ]);
    }
}
