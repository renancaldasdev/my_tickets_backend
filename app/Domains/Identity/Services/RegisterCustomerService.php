<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\Customer;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterCustomerService
{
    public function handle(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $slug = Str::slug($data['company_name']);

            /** @var Customer $customer */
            $customer = Customer::create([
                'name' => $data['company_name'],
                'company_name' => $data['company_name'],
                'document' => $data['document'],
                'email' => $data['email'],
                'domain' => $slug.'.seusistema.com',
            ]);

            /** @var BusinessUnit $defaultBU */
            $defaultBU = $customer->businessUnits()->create([
                'name' => 'Matriz',
                'slug' => 'matriz-'.$customer->id,
                'is_active' => true,
            ]);

            /** @var User $user */
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'customer_id' => $customer->id,
                'business_unit_id' => $defaultBU->id,
                'role' => 'manager',
            ]);
            $user->assignRole('manager');

            return [
                'user' => $user,
                'customer' => $customer,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ];
        });
    }
}
