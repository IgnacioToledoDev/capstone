<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Helper\CarHelper;
use App\Helper\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\Maintenance;
use App\Models\MaintenanceDetails;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\StatusCar;
use App\Models\TypeService;
use App\Models\User;
use Carbon\Carbon;
use http\Exception\BadHeaderException;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MaintenanceController extends Controller
{
    private CarHelper $carHelper;
    private UserHelper $userHelper;
    public function __construct(CarHelper $carHelper, UserHelper $userHelper)
    {
        $this->userHelper = $userHelper;
        $this->carHelper = $carHelper;
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/maintenance/calendar",
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
                ->whereDate('start_maintenance', Carbon::today()) // Solo registros de hoy
                ->whereTime('start_maintenance', '<', Carbon::now()->format('H:i:s')) // Hora menor a la actual
                ->whereIn('status_id', [StatusCar::STATUS_INACTIVE])
                ->get();

            $current = $this->getCurrentClient($user);

            if (!empty($current)) {
                $index = 0;
                foreach ($calendar as $item) {
                    $currentMaintenance = $current[0]['maintenance'];
                    if ($currentMaintenance->id === $item->id) {
                        unset($calendar[$index]);
                    }
                    $index++;
                }
            }

            $success['calendar'] = $calendar;
            $success['current'] = $current;

            return $this->sendResponse($success, 'Calendar retrieved successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getLine());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/maintenance/update/current-client",
     *     summary="Actualizar el cliente actual del usuario autenticado",
     *     description="Este endpoint actualiza el cliente actual para el usuario autenticado y devuelve la información del cliente actualizado.",
     *     operationId="updateCurrentClient",
     *     tags={"Maintenances"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cliente actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current", type="object", description="Información del cliente actual",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Calendar retrieved successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="user not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
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
     *     tags={"Maintenances"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="carId", type="integer", example=1),
     *              @OA\Property(property="recommendation_action", type="string", example="Cambio de aceite y filtro"),
     *              @OA\Property(
     *               property="services",
     *              type="array",
     *              @OA\Items(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1)
     *              ),
     *              example={{"id": 1}, {"id": 2}}
     *              ),
     *              @OA\Property(property="startNow", type="boolean", example=true),
     *          )
     *      ),
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
     *                 @OA\Property(property="car_id", type="integer", example=1),
     *                 @OA\Property(property="mechanic_id", type="integer", example=5),
     *                 @OA\Property(property="pricing", type="number", example=12050),
     *                 @OA\Property(property="start_maintenance", type="string", format="date-time", example="2024-10-10T10:00:00Z"),
     *                 @OA\Property(property="end_maintenance", type="string", format="date-time", example="2024-10-10T12:00:00Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-30T10:45:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-30T10:45:00Z")
     *             ),
     *             @OA\Property(property="maintenanceDetails", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="maintenance_id", type="integer", example=10),
     *                     @OA\Property(property="service_id", type="integer", example=1),
     *                     @OA\Property(property="quotation_id", type="integer", example=null),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-30T10:45:00Z")
     *                 )
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
     *             @OA\Property(property="message", type="string", example="Service or mechanic not found.")
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

        $carId = $request->input('carId');
        $notes = $request->input('recommendation_action');
        $services = $request->input('services');
        $error = [];
        $totalPricing = 0;


        $mechanic = auth()->user();
        if(!$mechanic) {
            return $this->sendError('mechanic not found');
        }


        $starNow = $request->input('startNow');
        $starNowDate = null;
        $status = StatusCar::STATUS_INACTIVE;
        if ($starNow === true) {
            $starNowDate = now();
            $status = StatusCar::STATUS_STARTED;
        }

        $maintenance = new Maintenance();
        $maintenance->name = $this->generateName($carId);
        $maintenance->recommendation_action = $notes ?? null;
        $maintenance->status_id = $status;
        $maintenance->car_id = $carId;
        $maintenance->mechanic_id = $mechanic->id;
        $maintenance->pricing = 0;
        $maintenance->start_maintenance = $starNowDate;
        $maintenance->end_maintenance = null;
        $maintenance->save();

        if($maintenance->getAttribute('id') == null) {
            return $this->sendError('maintenance not saved');
        }
        $maintenanceDetailsList = [];

        foreach ($services as $service) {
            $serviceFound = Service::whereId($service['id'])->first();
            if(!$serviceFound){
                $error[] = $serviceFound;
            }

            $maintenanceDetails = new MaintenanceDetails();
            $maintenanceDetails->maintenance_id = $maintenance->id;
            $maintenanceDetails->service_id = $serviceFound->id;
            $maintenanceDetails->quotation_id = null;
            $maintenanceDetails->save();
            $maintenanceDetailsList[] = $maintenanceDetails;
            $totalPricing += $serviceFound['price'];
        }

        if(!empty($error)) {
            $this->sendError('service not found');
        }

        $maintenance->pricing = $totalPricing;
        $maintenance->save();
        $success['maintenance'] = $maintenance;
        $success['maintenanceDetails'] = $maintenanceDetailsList ?? null;

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

    private function generateName(int $carId): string {
      $carName = $this->carHelper->getFullName($carId);
      $date = now();

      return 'Mantecion para el auto ' . $carName . ' ' . $date->format('d-m-Y H:i:s');
    }

    /**
     * @param User $user
     * @return array
     */
    private function getCurrentClient(User $user): array
    {
        $currentMaintenances = Maintenance::where('mechanic_id', $user->id)
            ->whereDate('start_maintenance', now()->toDateString())
            ->whereIn('status_id', [StatusCar::STATUS_STARTED, StatusCar::STATUS_PROGRESS])
            ->get();

        $currentClients = [];

        foreach ($currentMaintenances as $maintenance) {
            if ($maintenance->start_maintenance) {
               if (is_null($maintenance->car_id)) {
                  return [];
               }

               $car = Car::whereId($maintenance->car_id)->first();
               if (is_null($car)) {
                  return [];
               }
               $client = User::whereId($car->owner_id)->first();
               unset($client->password);

               $currentClients[] = [
                   'maintenance' => $maintenance,
                   'client' => $client,
                   'car' => [
                       'id' => $car->id,
                       'brand' => $this->carHelper->getCarBrandName($car->id),
                       'model' => $this->carHelper->getCarModelName($car->id),
                       'year' => $car->year,
                       'patent' => $car->patent,
                   ]
               ];
            }
        }

        return $currentClients;
    }


    /**
     * @OA\Get(
     *     path="/api/jwt/maintenance/historical",
     *     summary="Get maintenance history for authenticated mechanic",
     *     description="Retrieve the maintenance history of the authenticated mechanic, ordered by the start of the maintenance in descending order.",
     *     tags={"Mechanic"},
     *     security={{ "bearerAuth":{} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Historial retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="User not found or unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="user not found"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Mechanic not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="mechanic not found"
     *             )
     *         )
     *     )
     * )
     */
    public function getHistorical(Request $request): JsonResponse
    {
        if(!auth()->check()) {
            return $this->sendError('user not found');
        }

        $mechanic = auth()->user();
        if (!$mechanic) {
            return $this->sendError('mechanic not found');
        }

        $maintenances = Maintenance::whereMechanicId($mechanic->id)
            ->orderBy('start_maintenance', 'desc')
            ->get();
        $historical = [];

        foreach ($maintenances as $maintenance) {
            $car = Car::whereId($maintenance->car_id)->first();
            $owner = User::whereId($car->owner_id)->first();
            unset($owner->mechanic_score);
            unset($owner->password);
            $fullNameCar = $this->carHelper->getFullName($car->id);
            $carObject = [
                'id' => $car->id,
                'patent' => $car->patent,
                'brand' => $this->carHelper->getCarBrandName($car->id),
                'model' => $this->carHelper->getCarModelName($car->id),
                'year' => $car->year,
                'fullName' => $fullNameCar,
            ];

            $record = [
                'maintenance' => $maintenance,
                'car' => $carObject,
                'owner' => $owner,
            ];
            $historical[] = $record;
        }

        $success['historical'] = $historical;

        return $this->sendResponse($success, 'Historial retrieved successfully.');
    }

    /**
     * @OA\Get(
     *      path="/api/jwt/maintenance/historical/{id}",
     *      summary="Get detailed maintenance history for a specific maintenance ID",
     *      description="Retrieve detailed historical information for a specific maintenance record, including details of the car and client.",
     *      tags={"Mechanic"},
     *      security={{ "bearerAuth":{} }},
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The ID of the maintenance record to retrieve",
     *          @OA\Schema(
     *              type="integer",
     *              example=123
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Historial retrieved successfully",
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="User not found or unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="user not found"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Maintenance not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="maintenance not found"
     *             )
     *         )
     *     ),
     * )
     */
    public function getMaintenanceHistoricalInformation(int $maintenanceId): JsonResponse
    {
        if(!auth()->check()) {
            return $this->sendError('user not found');
        }

        $maintenance = Maintenance::whereId($maintenanceId)->first();
        if (!$maintenance) {
            return $this->sendError('maintenance not found');
        }
        $maintenanceDetails = MaintenanceDetails::whereMaintenanceId($maintenance->id)->get();
        $details = [];
        if($maintenanceDetails->count() === 0) {
            $details = [
                'message' => 'Maintenance not have details',
            ];
        }
        foreach ($maintenanceDetails as $maintenanceDetail) {
            $services = Service::whereId($maintenanceDetail->service_id)->get();
            $arrServices = [];
            foreach ($services as $service) {
                $serviceFound = Service::whereId($service->id)->first();
                $arrServices[] = $serviceFound;
                $details = [
                    'services' => $arrServices
                ];
            }
        }

        $carId = $maintenance->toArray()['car_id'];
        $car = Car::whereId($carId)->first();
        $clientId = $car->owner_id;
        $client = User::whereId($clientId)->first();
        if(!$client || !$car) {
            return $this->sendError('client or car not found');
        }

        $brandName = $this->carHelper->getCarBrandName($car->brand_id);
        $modelName = $this->carHelper->getCarModelName($car->brand_id);
        unset($client->password);
        unset($client->mechanic_score);
        $car->brand_id = $brandName;
        $car->model_id = $modelName;

        $success['maintenance'] = $maintenance;
        $success['details'] = $details;
        $success['client'] = $client;
        $success['car'] = $car;

        return $this->sendResponse($success, 'Historial retrieved successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/maintenance/{maintenanceId}/status",
     *     tags={"Maintenances"},
     *     summary="Obtener el estado de un mantenimiento",
     *     description="Devuelve el estado de un mantenimiento específico por su ID.",
     *     security={{ "bearerAuth":{} }},
     *
     *     @OA\Parameter(
     *         name="maintenanceId",
     *         in="path",
     *         required=true,
     *         description="ID del mantenimiento que se desea consultar.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado del mantenimiento recuperado exitosamente.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mantenimiento no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="maintenance not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="user not found")
     *         )
     *     )
     * )
     */
    public function getStatus($maintenanceId): JsonResponse
    {
        $maintenance = Maintenance::whereId($maintenanceId)->first();
        if (!$maintenance) {
            return $this->sendError('maintenance not found');
        }

        $status = StatusCar::find($maintenance->status_id);
        $success['status'] = $status;
        return $this->sendResponse($success, 'Status retrieved successfully.');
    }

    /**
     * @OA\Post(
     *     path="/api/jwt/maintenance/{maintenanceId}/status/next",
     *     tags={"Maintenances"},
     *     summary="Cambiar el estado de un mantenimiento",
     *     description="Cambia el estado de un mantenimiento específico por su ID.",
     *     security={{ "bearerAuth":{} }},
     *
     *     @OA\Parameter(
     *         name="maintenanceId",
     *         in="path",
     *         required=true,
     *         description="ID del mantenimiento cuyo estado se desea cambiar.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado del mantenimiento cambiado exitosamente.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mantenimiento no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="maintenance not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="user not found")
     *         )
     *     )
     * )
     */
    public function changeStatus(Request $request, int $maintenanceId): JsonResponse
    {
        if (!auth()->check()) {
            return $this->sendError('user not found');
        }

        $maintenance = Maintenance::find($maintenanceId);
        if (!$maintenance) {
            return $this->sendError('maintenance not found');
        }

        $actualStatus = $maintenance->status_id;

        if ($actualStatus < StatusCar::STATUS_FINISHED) {
            if(!isset($maintenance->start_maintenance)) {
                $maintenance->start_maintenance = now();
            }
            $newStatus = $actualStatus + 1;
            $maintenance->status_id = $newStatus;

            $nextStatus = StatusCar::find($newStatus);
        } else {
            $maintenance->end_maintenance->format('d-m-Y H:i:s');
            $maintenance->save();
        }

        $maintenance->save();

        $status = StatusCar::find($maintenance->status_id);
        $nextStatusObj = StatusCar::find($maintenance->status_id + 1);

        $success['wasChanged'] = true;
        $success['actualStatus'] = $status->status;
        $success['nextStatus'] = $nextStatusObj ? $nextStatusObj->status : false;

        return $this->sendResponse($success, 'maintenance status changed successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/maintenanceDetails/{maintenanceId}",
     *     summary="Obtener detalles de una mantención específica",
     *     description="Este endpoint permite obtener los detalles de una mantención, incluyendo información del vehículo, del dueño y los servicios realizados.",
     *     tags={"Maintenances"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="maintenanceId",
     *         in="path",
     *         required=true,
     *         description="ID de la mantención",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles obtenidos con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="owner",
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890")
     *                 ),
     *                 @OA\Property(
     *                     property="services",
     *                     type="object",
     *                     additionalProperties=@OA\Property(type="string", example="Cambio de aceite")
     *                 ),
     *                 @OA\Property(
     *                     property="car",
     *                     type="object",
     *                     @OA\Property(property="patent", type="string", example="ABC123"),
     *                     @OA\Property(property="brand", type="string", example="Toyota"),
     *                     @OA\Property(property="model", type="string", example="Corolla"),
     *                     @OA\Property(property="year", type="integer", example=2020)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Get all data successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Usuario no autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="user not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mantención o vehículo no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Maintenance or car not found")
     *         )
     *     )
     * )
     */
    public function getMaintenanceDetails(int $maintenanceId): JsonResponse
    {
        try {
            $maintenance = Maintenance::whereId($maintenanceId)->first();
            $car = Car::whereId($maintenance->car_id)->first();
            $details = MaintenanceDetails::whereMaintenanceId($maintenance->id)->get();
            if(empty($maintenance || empty($car))) {
                throw new NotFoundHttpException('Maintenance or car not found');
            }

            $services = [];
            foreach ($details as $detail) {
                $service = Service::whereId($detail->service_id)->first();
                if (!empty($service)) {
                    $services[] = $service;
                }
            }
            $status = StatusCar::where(['id' => $maintenance->status_car_id])->first();
            $carModel = $this->carHelper->getCarModelName($car->model_id);
            $brand = $this->carHelper->getCarBrandName($car->brand_id);
            $owner = User::where(['id' => $car->owner_id ])->first();

            $success['maintenance'] = $maintenance;
            $success['maintenanceStatus'] = $status;
            $success['owner'] =  [
                'name' => $owner->name,
                'email' => $owner->email,
                'phone' => $owner->phone,
            ];
            $success['services'] = $services;
            $success['car'] = [
                'patent' => $car->patent,
                'brand' => $brand,
                'model' => $carModel,
                'year' => $car->year
            ];

            return $this->sendResponse($success, 'Get all data successfully.');
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage(), $exception->getLine());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/maintenance/inCourse",
     *     summary="Obtener detalles de la mantención en curso",
     *     description="Este endpoint permite obtener los detalles de la mantención en curso de un vehículo asociado al usuario autenticado, incluyendo información del vehículo, del dueño y los servicios realizados.",
     *     tags={"Maintenances"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Detalles obtenidos con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="owner",
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890")
     *                 ),
     *                 @OA\Property(
     *                     property="services",
     *                     type="array",
     *                     @OA\Items(type="string", example="Cambio de aceite")
     *                 ),
     *                 @OA\Property(
     *                     property="car",
     *                     type="object",
     *                     @OA\Property(property="patent", type="string", example="ABC123"),
     *                     @OA\Property(property="brand", type="string", example="Toyota"),
     *                     @OA\Property(property="model", type="string", example="Corolla"),
     *                     @OA\Property(property="year", type="integer", example=2020)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Maintenance in course found successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Usuario no autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not authenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mantención o vehículo no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Maintenance or car not found.")
     *         )
     *     )
     * )
     */
    public function getMaintenanceInCourse(Request $request): JsonResponse
    {
        $user = auth()->user();

        $cars = Car::whereOwnerId($user->id)->get();
        $carIds = [];
        foreach ($cars as $carId) {
            $carIds[] = $carId->id;
        }

        $maintenanceInCourse = Maintenance::whereIn('car_id', $carIds)
            ->where('status_id', [StatusCar::STATUS_STARTED, StatusCar::STATUS_PROGRESS])
            ->first();

        if (!$maintenanceInCourse) {
            return $this->sendError('No maintenance in course found.', 404); // Retornar un error si no se encuentra
        }

        $car = Car::whereId($maintenanceInCourse->car_id)->first();
        $status = StatusCar::whereId($maintenanceInCourse->status_id)->first();

        $success['maintenanceInCourse'] = [
            'maintenance' => $maintenanceInCourse,
            'status' => $status->status,
            'car' => [
                'patent' => $car->patent,
                'brand' => $this->carHelper->getCarBrandName($car->id),
                'model' => $this->carHelper->getCarModelName($car->id),
                'year' => $car->year
            ]
        ];

        return $this->sendResponse($success['maintenanceInCourse'], 'maintenance in course founded successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/maintenance/{userId}/all",
     *     summary="Obtener mantenciones por ID de usuario",
     *     description="Este endpoint obtiene todas las mantenciones de los vehículos asociados a un usuario específico.",
     *     tags={"Maintenances"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario para obtener sus mantenciones",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mantenciones obtenidas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="maintenances",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=101),
     *                         @OA\Property(property="car_id", type="integer", example=5),
     *                         @OA\Property(property="start_date", type="string", format="date", example="2024-11-07"),
     *                         @OA\Property(property="end_date", type="string", format="date", example="2024-11-10"),
     *                         @OA\Property(property="status", type="string", example="completed"),
     *                         @OA\Property(property="description", type="string", example="Cambio de aceite y filtro"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-01T14:30:00Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-05T18:00:00Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario o vehículos no encontrados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Usuario o vehículos no encontrados"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Ocurrió un error al obtener las mantenciones"
     *             )
     *         )
     *     )
     * )
     */
    public function getMaintenancesByUserId(int $userId): JsonResponse
    {
        $user = User::whereId($userId)->first();
        $cars = Car::whereOwnerId($user->id)->get();
        $maintenancesArr = [];
        foreach ($cars as $car) {
            $maintenances = Maintenance::whereCarId($car->id)->get();
            foreach ($maintenances as $maintenance) {
                $maintenancesArr[] = $maintenance;
            }
        }
        $success['maintenances'] = $maintenancesArr;
        return $this->sendResponse($success, 'maintenances found.');
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/maintenance/{userId}/historical",
     *     summary="Obtener mantenimientos por ID de usuario",
     *     description="Este endpoint obtiene todos los mantenimientos asociados a los vehículos de un usuario específico.",
     *     tags={"Maintenances"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario propietario de los vehículos",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mantenimientos encontrados",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="maintenances found."
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="JWT Token no found o is not valid")
     *         )
     *     )
     * )
     */
    public function getHistoricalMaintenanceByUserId(int $userId): JsonResponse
    {
        $user = User::whereId($userId)->first();
        $cars = Car::whereOwnerId($user->id)->get();
        $maintenancesArr = [];

        foreach ($cars as $car) {
            $maintenances = Maintenance::whereCarId($car->id)->get();

            foreach ($maintenances as $maintenance) {
                $mechanic = User::whereId($maintenance->mechanic_id)->first();

                $detailsArr = [];
                $details = MaintenanceDetails::whereMaintenanceId($maintenance->id)->get();
                foreach ($details as $detail) {
                    $service = Service::whereId($detail->service_id)->first();
                    $detailsArr[] = [
                        'details' => $detail,
                        'service' => $service
                    ];
                }

                // Agregamos la entrada de mantenimiento única
                $response = [
                    'maintenance' => $maintenance,
                    'car' => [
                        'id' => $car->id,
                        'patent' => $car->patent,
                        'brand' => $this->carHelper->getCarBrandName($car->id),
                        'model' => $this->carHelper->getCarModelName($car->id),
                        'year' => $car->year
                    ],
                    'mechanic' => $mechanic,
                    'details' => $detailsArr, // Añadimos todos los detalles agrupados
                ];
                $maintenancesArr[] = $response;
            }
        }

        $success['maintenances'] = $maintenancesArr;

        return $this->sendResponse($success, 'Maintenances found.');
    }
}
