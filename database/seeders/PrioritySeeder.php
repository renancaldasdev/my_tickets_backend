<?php

namespace Database\Seeders;

use App\Domains\Core\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            ['name' => 'Baixa', 'slug' => 'low'],
            ['name' => 'Média', 'slug' => 'medium'],
            ['name' => 'Alta', 'slug' => 'high'],
            ['name' => 'Crítica', 'slug' => 'critical'],
        ];

        foreach ($priorities as $priority) {
            Priority::create($priority);
        }
    }
}
