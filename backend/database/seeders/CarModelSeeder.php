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
        /**Inicio aporte Jose.*/
        DB::table('car_model')->insert([
            'name' => 'Jimny',
            'brand_id' => 1
        ]);

        DB::table('car_model')->insert([
            'name' => 'Celerio',
            'brand_id' => 1
        ]);

        DB::table('car_model')->insert([
            'name' => 'Alto',
            'brand_id' => 1
        ]);

        DB::table('car_model')->insert([
            'name' => 'Yaris',
            'brand_id' => 2
        ]);

        DB::table('car_model')->insert([
            'name' => 'Corolla',
            'brand_id' => 2
        ]);

        DB::table('car_model')->insert([
            'name' => 'Raize',
            'brand_id' => 2
        ]);

        DB::table('car_model')->insert([
            'name' => 'Kicks',
            'brand_id' => 3
        ]);

        DB::table('car_model')->insert([
            'name' => 'Qashqai',
            'brand_id' => 3
        ]);

        DB::table('car_model')->insert([
            'name' => 'Versa',
            'brand_id' => 3
        ]);

        DB::table('car_model')->insert([
            'name' => 'rx7',
            'brand_id' => 4
        ]);

        DB::table('car_model')->insert([
            'name' => 'CX-9',
            'brand_id' => 4
        ]);

        DB::table('car_model')->insert([
            'name' => 'CX-90',
            'brand_id' => 4
        ]);

        DB::table('car_model')->insert([
            'name' => 'CX-60',
            'brand_id' => 4
        ]);
        /**Marca Hyundai.*/
        DB::table('car_model')->insert([
            'name' => 'Santa Fe',
            'brand_id' => 6
        ]);

        DB::table('car_model')->insert([
            'name' => 'Tucson',
            'brand_id' => 6
        ]);

        DB::table('car_model')->insert([
            'name' => 'New Accent',
            'brand_id' => 6
        ]);
        /**Marca Ford.*/
        DB::table('car_model')->insert([
            'name' => 'Territory',
            'brand_id' => 7
        ]);

        DB::table('car_model')->insert([
            'name' => 'Explorer',
            'brand_id' => 7
        ]);

        DB::table('car_model')->insert([
            'name' => 'New Escape',
            'brand_id' => 7
        ]);
        /**Fin aporte Jose.*/
        DB::table('car_model')->insert([
            'name' => 'Otro',
            'brand_id' => 5
        ]);
    }
}
