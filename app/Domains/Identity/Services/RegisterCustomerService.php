<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Jobs\SendVerificationEmailJob;
use App\Domains\Identity\Models\BusinessUnit;
use App\Domains\Identity\Models\Customer;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterCustomerService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{user: User, customer: Customer}
     */
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
                'role' => 'manager',
            ]);

            $user->assignRole('manager');

            /** @var BelongsToMany<BusinessUnit, User> $buRelation */
            $buRelation = $user->businessUnits();
            $buRelation->attach($defaultBU->id);

            SendVerificationEmailJob::dispatch($user);

            return [
                'user' => $user,
                'customer' => $customer,
            ];
        });
    }
}
