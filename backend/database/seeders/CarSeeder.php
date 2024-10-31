<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cars')->insert([
            'brand_id' => 1,
            'model_id' => 1,
            'year' => 2018,
            'owner_id' => 1,
            'mechanic_id' => 5,
        ]);

        DB::table('cars')->insert([
            'brand_id' => 2,
            'model_id' => 2,
            'year' => 2013,
            'owner_id' => 2,
            'mechanic_id' => 4
        ]);
    }
}
