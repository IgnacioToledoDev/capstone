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
        DB::table('type_services')->insert([
            [
                'name' => 'Inspección y Diagnóstico',
                'description' => 'Inspección y diagnóstico completo para evaluar el estado del vehículo e identificar posibles problemas.',
            ],
            [
                'name' => 'Mantenimiento Preventivo',
                'description' => 'Mantenimiento regular orientado a prevenir problemas potenciales y asegurar que el vehículo funcione eficientemente.',
            ],
            [
                'name' => 'Mantenimiento Correctivo',
                'description' => 'Mantenimiento enfocado en corregir problemas identificados y restaurar el vehículo a su estado de funcionamiento adecuado.',
            ],

        ]);
    }
}
