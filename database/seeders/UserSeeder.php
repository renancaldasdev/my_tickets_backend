<?php

namespace Database\Seeders;

use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\Customer;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::where('domain', 'alfa.tickets.com')->first();
        $buTI = BusinessUnit::where('slug', 'suporte-ti')->first();
        $buMatriz = BusinessUnit::where('slug', 'matriz-hq')->first();

        $password = Hash::make('password123');

        User::create([
            'name' => 'Desenvolvedor Master',
            'email' => 'dev@sistema.com',
            'password' => $password,
            'customer_id' => null,
            'role' => 'dev',
        ])->assignRole('dev');

        User::create([
            'name' => 'Gerente Alfa',
            'email' => 'manager@alfa.com',
            'password' => $password,
            'customer_id' => $customer->id,
            'role' => 'manager',
        ])->assignRole('manager');

        $agent = User::create([
            'name' => 'Agente Suporte TI',
            'email' => 'agent@alfa.com',
            'password' => $password,
            'customer_id' => $customer->id,
            'role' => 'agent',
        ]);
        $agent->assignRole('agent');
        $agent->businessUnits()->attach($buTI->id);

        $cliente = User::create([
            'name' => 'João Cliente',
            'email' => 'cliente@alfa.com',
            'password' => $password,
            'customer_id' => $customer->id,
            'role' => 'user',
        ]);
        $cliente->assignRole('user');
        $cliente->businessUnits()->attach($buMatriz->id);
    }
}
