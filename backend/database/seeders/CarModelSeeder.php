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
            'brand_id' => 1
        ]);

        DB::table('car_model')->insert([
            'name' => 'Yarias',
            'brand_id' => 1
        ]);

        DB::table('car_model')->insert([
            'name' => 'Kicks',
            'brand_id' => 1
        ]);

        DB::table('car_model')->insert([
            'name' => 'rx7',
            'brand_id' => 1
        ]);

        DB::table('car_model')->insert([
            'name' => 'Otro',
            'brand_id' => 5
        ]);
    }
}
