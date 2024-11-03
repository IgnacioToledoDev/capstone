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
                'price' => 12500
            ],
            [
                'name' => 'Revisión de Frenos',
                'description' => 'Inspección del sistema de frenos, incluyendo discos, pastillas y líquido de frenos, para garantizar seguridad.',
                'type_id' => $type->id,
                'price' => 20000
            ],
            [
                'name' => 'Alineación y Balanceo',
                'description' => 'Ajuste de la alineación de las ruedas y balanceo de neumáticos para mejorar la estabilidad y reducir el desgaste irregular.',
                'type_id' => $type->id,
                'price' => 10000
            ],
            [
                'name' => 'Cambio de Filtros de Aire',
                'description' => 'Reemplazo del filtro de aire para mejorar la calidad del aire en el motor y optimizar el rendimiento del vehículo.',
                'type_id' => $type->id,
                'price' => 10000
            ],
            [
                'name' => 'Revisión del Sistema Eléctrico',
                'description' => 'Diagnóstico completo del sistema eléctrico del vehículo, incluyendo batería, alternador y componentes eléctricos.',
                'type_id' => $type->id,
                'price' => 10000
            ],
            /**Inicio aporte Jose.*/
            [
                'name' => 'Cambio de aceite y filtro',
                'description' => 'Es uno de los servicios más frecuentes, ya que el aceite y el filtro deben reemplazarse regularmente para asegurar el buen funcionamiento del motor.',
                'type_id' => 2,
                'price' => 15000
            ],
            [
                'name' => 'Revisión y reemplazo de frenos',
                'description' => 'La inspección y cambio de pastillas, discos y líquido de frenos son esenciales para mantener la seguridad del vehículo.',
                'type_id' => 3,
                'price' => 20000
            ],
            [
                'name' => 'Revisión de suspensión y amortiguadores',
                'description' => 'La suspensión afecta directamente el confort y la estabilidad del vehículo, por lo que su revisión es fundamental.',
                'type_id' => 1,
                'price' => 18500
            ],
            [
                'name' => 'Cambio de filtros de aire y combustible',
                'description' => 'Estos filtros son importantes para el rendimiento y la eficiencia del motor. Se revisan y reemplazan según el uso y las recomendaciones del fabricante.',
                'type_id' => 2,
                'price' => 10000
            ],
            [
                'name' => 'Revisión del sistema de refrigeración',
                'description' => 'Incluye verificar el nivel y el estado del refrigerante, además de inspeccionar el radiador y las mangueras para evitar sobrecalentamientos.',
                'type_id' => 1,
                'price' => 25000
            ],
            [
                'name' => 'Revisión y carga de batería',
                'description' => 'Los talleres revisan el estado de la batería y, si es necesario, la recargan o reemplazan.',
                'type_id' => 3,
                'price' => 16000
            ],
            [
                'name' => 'Cambio de bujías',
                'description' => 'Las bujías deben revisarse y, si es necesario, cambiarse para asegurar una combustión eficiente del motor.',
                'type_id' => 2,
                'price' => 12500
            ],
            /**Fin aporte Jose.*/
        ]);
    }
}
