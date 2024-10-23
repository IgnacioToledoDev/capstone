<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('car_model')->insert([
            'name' => 'Swift',
        ]);

        DB::table('car_model')->insert([
            'name' => 'Yarias',
        ]);

        DB::table('car_model')->insert([
            'name' => 'Kicks',
        ]);

        DB::table('car_model')->insert([
            'name' => 'rx7',
        ]);

        DB::table('car_model')->insert([
            'name' => 'Otro',
        ]);
    }
}
