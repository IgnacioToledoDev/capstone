<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarBrandSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('car_brands')->insert([
            'name' => 'Suzuki',
        ]);

        DB::table('car_brands')->insert([
            'name' => 'Toyota',
        ]);

        DB::table('car_brands')->insert([
            'name' => 'Nissan',
        ]);

        DB::table('car_brands')->insert([
            'name' => 'Mazda',
        ]);

        /**Inicio aporte Jose.*/
        DB::table('car_brands')->insert([
            'name' => 'Hyundai',
        ]);

        DB::table('car_brands')->insert([
            'name' => 'Ford',
        ]);
        /**Fin aporte Jose.*/
        DB::table('car_brands')->insert([
            'name' => 'Otro',
        ]);
    }
}
