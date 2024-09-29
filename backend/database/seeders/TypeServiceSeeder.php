<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    DB::table('maintenance_types')->insert([
        [
            'name' => 'Inspection and Diagnostic',
            'description' => 'Comprehensive inspection and diagnostic to assess the vehicle\'s condition and identify potential issues.',
        ],
        [
            'name' => 'Preventive Maintenance',
            'description' => 'Regular maintenance aimed at preventing potential problems and ensuring the vehicle runs efficiently.',
        ],
        [
            'name' => 'Corrective Maintenance',
            'description' => 'Maintenance focused on fixing identified issues and restoring the vehicle to its proper working condition.',
        ],
    ]);
}
}
