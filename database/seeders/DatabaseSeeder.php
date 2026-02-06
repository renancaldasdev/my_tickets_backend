<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            BusinessUnitSeeder::class,
            StatusSeeder::class,
            PrioritySeeder::class,
            CategorySeeder::class,
        ]);

        \App\Domains\Identity\Models\User::factory()->create([
            'name' => 'Admin Sistema',
            'email' => 'admin@empresa.com',
            'role' => 'admin',
            'business_unit_id' => 1,
        ]);
    }
}
