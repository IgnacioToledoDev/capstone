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

        $mechanic1 = User::create([
            'username' => 'mechanic_1',
            'name' => 'Carlos',
            'lastname' => 'Pérez',
            'email' => 'carlos.perez@taller.cl',
            'password' => Hash::make('CarlosP123.')
        ]);

        $mechanic2 = User::create([
            'username' => 'mechanic_2',
            'name' => 'María',
            'lastname' => 'González',
            'email' => 'maria.gonzalez@taller.cl',
            'password' => Hash::make('MariaG123.')
        ]);

        $mechanic3 = User::create([
            'username' => 'mechanic_3',
            'name' => 'Juan',
            'lastname' => 'Ramírez',
            'email' => 'juan.ramirez@taller.cl',
            'password' => Hash::make('JuanR123.')
        ]);

        $mechanic4 = User::create([
            'username' => 'mechanic_4',
            'name' => 'Ana',
            'lastname' => 'Martínez',
            'email' => 'ana.martinez@taller.cl',
            'password' => Hash::make('AnaM123.')
        ]);

        $mechanic5 = User::create([
            'username' => 'mechanic_5',
            'name' => 'Pedro',
            'lastname' => 'Lopez',
            'email' => 'pedro.lopez@taller.cl',
            'password' => Hash::make('PedroL123.')
        ]);

        $superAdmin->assignRole('SUPER_ADMIN');
        $testUser->assignRole('CUSTOMER_USER');
        $mechanic1->assignRole(User::MECHANIC);
        $mechanic2->assignRole(User::MECHANIC);
        $mechanic3->assignRole(User::MECHANIC);
        $mechanic4->assignRole(User::MECHANIC);
        $mechanic5->assignRole(User::MECHANIC);
    }
}
