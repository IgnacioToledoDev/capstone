<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_cars')->insert([
            'status' => 'Inactivo',
            'description' => 'When the car progress has not started.',
        ]);

        DB::table('status_cars')->insert([
            'status' => 'Comenzando',
            'description' => 'When the car progress has just started.',
        ]);

        DB::table('status_cars')->insert([
            'status' => 'En progreso',
            'description' => 'When the car is currently being worked on.',
        ]);

        DB::table('status_cars')->insert([
            'status' => 'Finalizado',
            'description' => 'When the car work has been completed.',
        ]);
    }
}
