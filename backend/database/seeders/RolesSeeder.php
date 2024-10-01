<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles para el guard 'web'
        Role::create(['name' => 'SUPER_ADMIN', 'guard_name' => 'web']);
        Role::create(['name' => 'COMPANY_ADMIN', 'guard_name' => 'web']);
        Role::create(['name' => 'MECHANIC_USER', 'guard_name' => 'web']);
        Role::create(['name' => 'CUSTOMER_USER', 'guard_name' => 'web']);

        // Roles para el guard 'api'
        Role::create(['name' => 'SUPER_ADMIN', 'guard_name' => 'api']);
        Role::create(['name' => 'COMPANY_ADMIN', 'guard_name' => 'api']);
        Role::create(['name' => 'MECHANIC_USER', 'guard_name' => 'api']);
        Role::create(['name' => 'CUSTOMER_USER', 'guard_name' => 'api']);
    }
}
