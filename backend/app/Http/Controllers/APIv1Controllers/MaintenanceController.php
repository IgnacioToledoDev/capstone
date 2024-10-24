<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Helper\CarHelper;
use App\Helper\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Maintenance;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\StatusCar;
use App\Models\TypeService;
use App\Models\User;
use http\Exception\BadHeaderException;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="maintenance", type="object",
     *                  @OA\Property(property="id", type="integer", example=10),
     *                  @OA\Property(property="name", type="string", example="Mantenimiento preventivo"),
     *                  @OA\Property(property="description", type="string", example="Cambio de aceite y revisión general"),
     *                  @OA\Property(property="status_id", type="integer", example=1),
     *                  @OA\Property(property="services", type="integer", example=1),
     *                  @OA\Property(property="car_id", type="integer", example=1),
     *              )
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
        $maintenance->start_maintenance->format('d-m-Y H:i:s');
        $maintenance->end_maintenance->format('d-m-Y H:i:s');
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
    private function getCurrentClient($user): array
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

        $success['historical'] = $maintenances;

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
        $car->brand_id = $brandName;
        $car->model_id = $modelName;

        $success['maintenance'] = $maintenance;
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
        if (!auth()->check()) {
            return $this->sendError('user not found');
        }

        $maintenance = Maintenance::whereId($maintenanceId)->first();
        if (!$maintenance) {
            return $this->sendError('maintenance not found');
        }

        $status = StatusCar::whereId($maintenance->status_id)->first();
        $success['actualStatus'] = $status;

        return $this->sendResponse($success, 'maintenance retrieved successfully.');
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
            if($actualStatus <= StatusCar::STATUS_STARTED) {
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
     *  @OA\Get(
     *      path="/quotations/{maintenanceId}/details",
     *      summary="Obtener detalles completos de una mantencion",
     *      description="Este endpoint permite obtener los detalles completos de una cotización, incluyendo información del auto, cliente, y los servicios aprobados.",
     *      tags={"Cotizaciones"},
     *
     *      @OA\Parameter(
     *          name="maintenanceId",
     *          in="path",
     *          description="ID de la cotización",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Detalles de la cotización obtenidos exitosamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="car",
     *                  type="object",
     *                  @OA\Property(property="patent", type="string", example="ABC123", description="Patente del auto"),
     *                  @OA\Property(property="brand", type="string", example="Toyota", description="Marca del auto"),
     *                  @OA\Property(property="model", type="string", example="Corolla", description="Modelo del auto")
     *              ),
     *              @OA\Property(
     *                  property="client",
     *                  type="object",
     *                  @OA\Property(property="name", type="string", example="Juan Pérez", description="Nombre completo del cliente"),
     *                  @OA\Property(property="email", type="string", example="juan.perez@example.com", description="Correo electrónico del cliente"),
     *                  @OA\Property(property="phone", type="string", example="123456789", description="Teléfono del cliente"),
     *                  @OA\Property(property="reservationData", type="string", example="2024-10-23", description="Fecha de la reserva")
     *              ),
     *              @OA\Property(
     *                  property="services",
     *                  type="array",
     *                  description="Servicios aprobados en la cotización",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1, description="ID del servicio"),
     *                      @OA\Property(property="name", type="string", example="Cambio de aceite", description="Nombre del servicio")
     *                  )
     *              ),
     *              @OA\Property(property="message", type="string", example="Get all data successfully.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Error de autenticación o de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="user not found")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="No se encontró la cotización",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Quotation does not exist")
     *          )
     *      ),
     *
     *      security={{"bearerAuth":{}}}
     *  )
     * /
     */
    public function getMaintenanceInfo(int $maintenanceId): JsonResponse
    {
        try {
            if (auth()->check()) {
                throw new BadHeaderException('user not found');
            }
            $maintenance = Maintenance::whereId($maintenanceId)->first();
            if (!$maintenance) {
                return $this->sendError('maintenance not found');
            }


            $quotation = Quotation::where(['id' => $quotationId])->first();
            $quotationDetails = QuotationDetails::whereQuotationId($quotationId)->get();
            $servicesOfQuotation = [];
            foreach ($quotationDetails as $quotationDetail) {
                if($quotationDetail->is_approved_by_client) {
                    $service = Service::whereId($quotationDetail->id)->first();
                    $servicesOfQuotation[$service->id] = $service->name;
                }
            }
            if(!$quotation){
                throw new NotFoundHttpException('Quotation does not exist');
            }
            $car = Car::whereId($quotation->car_id)->first();
            $carModel = $this->carHelper->getCarModelName($car->model_id);
            $brand = $this->carHelper->getCarBrandName($car->brand_id);
            $owner = User::whereId($car->onwer_id)->first();
            $reservation = Reservation::where(['car_id' => $car->id])->first();
            $dateReservation = !empty($reservation) ? $reservation->date_reservation : null;
            $fullName = $this->userHelper->getFullName($owner->id);

            $success['car'] = [
                'patent' => $car->patent,
                'brand' => $brand->name,
                'model' => $carModel->name,
            ];
            $success['client'] = [
                'name' => $fullName,
                'email' => $owner->email,
                'phone' => $owner->phone,
                'reservationData' => $dateReservation
            ];
            $success['services'] = $servicesOfQuotation;

            return $this->sendResponse($success, 'Get all data successfully.');

        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

}
