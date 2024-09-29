<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'Cambio de Aceite',
                'description' => 'Sustitución del aceite del motor para garantizar un rendimiento óptimo y prevenir desgaste prematuro.',
            ],
            [
                'name' => 'Revisión de Frenos',
                'description' => 'Inspección del sistema de frenos, incluyendo discos, pastillas y líquido de frenos, para garantizar seguridad.',
            ],
            [
                'name' => 'Alineación y Balanceo',
                'description' => 'Ajuste de la alineación de las ruedas y balanceo de neumáticos para mejorar la estabilidad y reducir el desgaste irregular.',
            ],
            [
                'name' => 'Cambio de Filtros de Aire',
                'description' => 'Reemplazo del filtro de aire para mejorar la calidad del aire en el motor y optimizar el rendimiento del vehículo.',
            ],
            [
                'name' => 'Revisión del Sistema Eléctrico',
                'description' => 'Diagnóstico completo del sistema eléctrico del vehículo, incluyendo batería, alternador y componentes eléctricos.',
            ],
        ]);
    }
}
