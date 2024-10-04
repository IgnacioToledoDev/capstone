<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Maintenance;
use App\Models\Service;
use App\Models\StatusCar;
use App\Models\TypeService;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class MaintenanceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/maintenance",
     *     summary="Obtiene el calendario de mantenimientos y clientes actuales para el mecánico autenticado",
     *     tags={"Maintenances"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Calendario y clientes actuales obtenidos exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="calendar",
     *                     type="array",
     *                     description="Lista de mantenimientos del día actual",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="car_id", type="integer", example=10),
     *                         @OA\Property(property="start_maintenance", type="string", format="date-time", example="2024-10-03 10:00:00"),
     *                         @OA\Property(property="mechanic_id", type="integer", example=2),
     *                         @OA\Property(property="description", type="string", example="Cambio de aceite")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="current",
     *                     type="array",
     *                     description="Lista de clientes actuales basados en el mantenimiento en curso",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Juan Pérez"),
     *                         @OA\Property(property="email", type="string", example="juan.perez@example.com")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado o token no válido",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="user not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la petición o falta de datos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="maintenance start_maintenance no available")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            if (empty($user->id)) {
                return $this->sendError('user not found');
            }

            $calendar = Maintenance::whereMechanicId($user->id)
                ->whereDate('start_maintenance', now()->toDateString())
                ->get();

            $current = $this->getCurrentClient($user);

            $success['calendar'] = $calendar;
            $success['current'] = $current;

            return $this->sendResponse($success, 'Calendar retrieved successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    
    public function updateCurrentClient(Request $request): JsonResponse
    {
        if(!auth()->check()) {
            return $this->sendError('user not found');
        }
        $user = auth()->user();
        $current = $this->getCurrentClient($user);

        $success['current'] = $current;
        return $this->sendResponse($success, 'Calendar retrieved successfully.');
    }

    /**
     * @OA\Post(
     *     path="/api/jwt/maintenance/create",
     *     summary="Register a new maintenance record",
     *     tags={"Maintenance"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\Response(
     *         response=200,
     *         description="Maintenance saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="maintenance", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="name", type="string", example="Mantenimiento preventivo"),
     *                 @OA\Property(property="description", type="string", example="Cambio de aceite y revisión general"),
     *                 @OA\Property(property="status_id", type="integer", example=1),
     *                 @OA\Property(property="services", type="integer", example=1),
     *                 @OA\Property(property="pricing", type="number", example=12050),
     *                 @OA\Property(property="car_id", type="integer", example=1),
     *                 @OA\Property(property="mechanic_id", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-30T10:45:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-30T10:45:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="JWT not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="JWT not authenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service or mechanic not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Service not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Maintenance not saved",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Maintenance not saved.")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return $this->sendError('JWT not authenticated.', 401);
        }

        $carId = $request->get('carId');
        $notes = $request->get('recommendation_action');
        $services = $request->get('services');
        $typeService = $request->get('typeService');
        $listServices = json_decode($services, true);
        $error = [];
        $totalPricing = 0;

        foreach ($listServices as $service) {
            $serviceFound = Service::whereId($service['id'])->first();
            if(!$serviceFound){
                $error = $serviceFound;
            }

            $totalPricing += $serviceFound['pricing'];
        }

        if(!empty($error)) {
            $this->sendError('service not found');
        }

        $mechanic = auth()->user();
        if(!$mechanic) {
            return $this->sendError('mechanic not found');
        }

        $maintenance = new Maintenance();
        $maintenance->name = $this->generateName($typeService);
        $maintenance->description = $notes ?? null;
        $maintenance->status_id = StatusCar::STATUS_INACTIVE;
        $maintenance->service_id = $listServices[0]['id'];
        $maintenance->actual_mileage = 4000;
        $maintenance->pricing = $totalPricing;
        $maintenance->car_id = $carId;
        $maintenance->mechanic_id = $mechanic->id;
        $maintenance->save();

        if($maintenance->getAttribute('id') == null) {
            return $this->sendError('maintenance not saved');
        }
        $success['maintenance'] = $maintenance;

        return $this->sendResponse($success, 'maintenance saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        //
    }

    private function generateName($typeServiceId): string {
        $typeService = TypeService::whereId($typeServiceId)->first();
        $now = new \DateTime('now');

        return 'new ' . $typeService->name . $now->format('d-m-Y');
    }

    /**
     * @param $user
     * @return array
     */
    public function getCurrentClient($user): array
    {
        if (!$user instanceof User) {
            throw new InvalidArgumentException('user not valid');
        }
        $currentMaintenances = Maintenance::whereMechanicId($user->id)
            ->whereDate('start_maintenance', now()->toDateString())
            ->whereTime('start_maintenance', now()->format('H:i'))
            ->get();

        $current = [];
        foreach ($currentMaintenances as $maintenance) {
            if ($maintenance->start_maintenance) {
                $car = Car::whereId($maintenance->car_id);
                $currentClient = User::whereId($car->owner_id);
                if ($currentClient) {
                    $current[] = $currentClient;
                }
            }
        }
        return $current;
    }
}
