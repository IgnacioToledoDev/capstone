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
            'model' => 'Swift',
            'year' => 2018,
            'user_id' => 1
        ]);

        DB::table('cars')->insert([
            'brand_id' => 2,
            'model' => 'Yaris',
            'year' => 2013,
            'user_id' => 2
        ]);
    }
}
