<?php

namespace Database\Seeders;

use App\Models\TypeService;
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
        $type = TypeService::where(['id' => 1])->first();

        DB::table('services')->insert([
            [
                'name' => 'Cambio de Aceite',
                'description' => 'Sustitución del aceite del motor para garantizar un rendimiento óptimo y prevenir desgaste prematuro.',
                'type_id' => $type->id,
            ],
            [
                'name' => 'Revisión de Frenos',
                'description' => 'Inspección del sistema de frenos, incluyendo discos, pastillas y líquido de frenos, para garantizar seguridad.',
                'type_id' => $type->id,
            ],
            [
                'name' => 'Alineación y Balanceo',
                'description' => 'Ajuste de la alineación de las ruedas y balanceo de neumáticos para mejorar la estabilidad y reducir el desgaste irregular.',
                'type_id' => $type->id,
            ],
            [
                'name' => 'Cambio de Filtros de Aire',
                'description' => 'Reemplazo del filtro de aire para mejorar la calidad del aire en el motor y optimizar el rendimiento del vehículo.',
                'type_id' => $type->id,
            ],
            [
                'name' => 'Revisión del Sistema Eléctrico',
                'description' => 'Diagnóstico completo del sistema eléctrico del vehículo, incluyendo batería, alternador y componentes eléctricos.',
                'type_id' => $type->id,
            ],
        ]);
    }
}
