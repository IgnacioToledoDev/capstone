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
            'status' => 'inactive',
            'description' => 'When the car progress has not started.',
        ]);

        DB::table('status_cars')->insert([
            'status' => 'started',
            'description' => 'When the car progress has just started.',
        ]);

        DB::table('status_cars')->insert([
            'status' => 'in progress',
            'description' => 'When the car is currently being worked on.',
        ]);

        DB::table('status_cars')->insert([
            'status' => 'finished',
            'description' => 'When the car work has been completed.',
        ]);
    }
}
