<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Service;
use App\Models\StatusCar;
use App\Models\User;
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
        $status = StatusCar::where(['id' => StatusCar::STATUS_INACTIVE])->first();
        $service1 = Service::where(['id' => 1])->first();
        $car1 = Car::where(['id' => 1])->first();
        $mechanic = User::where(['id' => 3])->first();

        DB::table('maintenances')->insert([
            [
                'name' => 'Cambio de Aceite y Filtro',
                'description' => 'Sustitución del aceite del motor y filtro para mantener el rendimiento óptimo del vehículo.',
                'status_id' => $status->id,
                'service_id' => $service1->id,
                'actual_mileage' => 15000,
                'recommendation_action' => 'Revisar el aceite cada 10,000 km y cambiar el filtro.',
                'pricing' => 50000,
                'car_id' => $car1->id,
                'mechanic_id' => $mechanic->id,
            ],
            [
                'name' => 'Revisión Completa de Frenos',
                'description' => 'Inspección y mantenimiento del sistema de frenos, incluyendo discos y pastillas.',
                'status_id' => $status->id,
                'service_id' => $service1->id,
                'actual_mileage' => 20000,
                'recommendation_action' => 'Reemplazar pastillas de freno si el desgaste es mayor al 50%.',
                'pricing' => 100000,
                'car_id' => $car1->id,
                'mechanic_id' => $mechanic->id,
            ],
            [
                'name' => 'Alineación y Balanceo de Ruedas',
                'description' => 'Ajuste de alineación y balanceo para mejorar la estabilidad del coche.',
                'status_id' => $status->id,
                'service_id' => $service1->id,
                'actual_mileage' => 25000,
                'recommendation_action' => 'Realizar alineación y balanceo cada 15,000 km.',
                'pricing' => 80000,
                'car_id' => $car1->id,
                'mechanic_id' => $mechanic->id,
            ]
        ]);
    }
}
