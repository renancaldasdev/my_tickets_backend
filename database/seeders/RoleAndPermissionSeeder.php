<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    const VIEW_TICKETS = 'view tickets';

    const CREATE_TICKETS = 'create tickets';

    const EDIT_TICKETS = 'edit tickets';

    const DELETE_TICKETS = 'delete tickets';

    const ASSIGN_TICKETS = 'assign tickets';

    const MANAGE_BUS = 'manage bus';

    const MANAGE_USERS = 'manage users';

    const GUARD_NAME = 'api';

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => self::VIEW_TICKETS, 'guard_name' => self::GUARD_NAME]);
        Permission::create(['name' => self::CREATE_TICKETS, 'guard_name' => self::GUARD_NAME]);
        Permission::create(['name' => self::EDIT_TICKETS, 'guard_name' => self::GUARD_NAME]);
        Permission::create(['name' => self::DELETE_TICKETS, 'guard_name' => self::GUARD_NAME]);
        Permission::create(['name' => self::ASSIGN_TICKETS, 'guard_name' => self::GUARD_NAME]);
        Permission::create(['name' => self::MANAGE_BUS, 'guard_name' => self::GUARD_NAME]);
        Permission::create(['name' => self::MANAGE_USERS, 'guard_name' => self::GUARD_NAME]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => 'dev', 'guard_name' => self::GUARD_NAME]);

        $manager = Role::create(['name' => 'manager', 'guard_name' => self::GUARD_NAME]);
        $manager->givePermissionTo([
            self::VIEW_TICKETS,
            self::CREATE_TICKETS,
            self::EDIT_TICKETS,
            self::ASSIGN_TICKETS,
            self::MANAGE_USERS,
            self::MANAGE_BUS,
        ]);

        $agent = Role::create(['name' => 'agent', 'guard_name' => self::GUARD_NAME]);
        $agent->givePermissionTo([
            self::VIEW_TICKETS,
            self::CREATE_TICKETS,
            self::EDIT_TICKETS,
        ]);

        $user = Role::create(['name' => 'user', 'guard_name' => self::GUARD_NAME]);
        $user->givePermissionTo([
            self::VIEW_TICKETS,
            self::CREATE_TICKETS,
        ]);
    }
}
