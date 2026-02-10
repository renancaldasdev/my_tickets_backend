<?php

namespace Database\Seeders;

use App\Domains\Core\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Aberto', 'slug' => 'open'],
            ['name' => 'Em Progresso', 'slug' => 'in_progress'],
            ['name' => 'Pendente', 'slug' => 'pending'],
            ['name' => 'Resolvido', 'slug' => 'resolved'],
            ['name' => 'Fechado', 'slug' => 'closed'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
