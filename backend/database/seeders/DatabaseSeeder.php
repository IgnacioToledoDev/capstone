<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UserSeeder::class,
            CarBrandSeeder::class,
            CarModelSeeder::class,
            CarSeeder::class,
            TenantSeeder::class,
            StatusCarSeeder::class,
            TypeServiceSeeder::class,
            ServiceSeeder::class,
            MaintenanceSeeder::class,
            ContactTypeSeeder::class,
        ]);
    }
}
