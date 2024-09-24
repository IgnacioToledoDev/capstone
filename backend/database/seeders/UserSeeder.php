<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'username' => 'superadmin',
            'name' => 'super',
            'lastname' => 'admin',
            'email' => 'superadmin@autominder.cl',
            'password' => bcrypt('Testing1.')
        ]);

        $testUser = User::create([
            'username' => 'test_user',
            'name' => 'test',
            'lastname' => 'user',
            'email' => 'test_user@test.cl',
            'password' => bcrypt('Testing1.')
        ]);

        $superAdmin->assignRole('SUPER_ADMIN');
        $testUser->assignRole('CUSTOMER_USER');
    }
}
