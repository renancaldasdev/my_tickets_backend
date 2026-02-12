<?php

namespace Database\Seeders;

use App\Domains\Identity\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::create([
            'name' => 'Empresa Alfa Tech',
            'email' => 'contato@alfatech.com',
            'domain' => 'alfa.tickets.com',
            'document' => '12345678000199',
        ]);
    }
}
