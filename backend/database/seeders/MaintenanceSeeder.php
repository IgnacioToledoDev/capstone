<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('maintenances')->insert([
            [
                'name' => 'Cambio de Aceite y Filtro',
                'description' => 'Sustitución del aceite del motor y filtro para mantener el rendimiento óptimo del vehículo.',
                'status_id' => 1,
                'service_id' => 1,
                'actual_mileage' => 15000,
                'recommendation_action' => 'Revisar el aceite cada 10,000 km y cambiar el filtro.',
                'pricing' => 50000,
                'car_id' => 1,
                'mechanic_id' => 3,
            ],
            [
                'name' => 'Revisión Completa de Frenos',
                'description' => 'Inspección y mantenimiento del sistema de frenos, incluyendo discos y pastillas.',
                'status_id' => 2,
                'service_id' => 2,
                'actual_mileage' => 20000,
                'recommendation_action' => 'Reemplazar pastillas de freno si el desgaste es mayor al 50%.',
                'pricing' => 100000,
                'car_id' => 2,
                'mechanic_id' => 4,
            ],
            [
                'name' => 'Alineación y Balanceo de Ruedas',
                'description' => 'Ajuste de alineación y balanceo para mejorar la estabilidad del coche.',
                'status_id' => 3,
                'service_id' => 3,
                'actual_mileage' => 25000,
                'recommendation_action' => 'Realizar alineación y balanceo cada 15,000 km.',
                'pricing' => 80000,
                'car_id' => 3,
                'mechanic_id' => 5,
            ]
        ]);
    }
}
