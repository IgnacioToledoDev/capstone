<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Helper\CarHelper;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Maintenance;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Service;
use App\Models\User;
use Doctrine\DBAL\Exception\NoActiveTransaction;
use http\Exception\BadHeaderException;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use OpenApi\Annotations as OA;

class QuotationController extends Controller
{
    private CarHelper $carHelper;
    public function __construct(CarHelper $carHelper)
    {
        $this->carHelper = $carHelper;
    }

    /**
     * @OA\Post(
     *     path="/api/jwt/quotations/create",
     *     summary="Crear una cotización",
     *     description="Este endpoint crea una nueva cotización para un auto, incluyendo los servicios solicitados y su estado de aprobación.",
     *     tags={"Quotations"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear una cotización",
     *         @OA\JsonContent(
     *             required={"carId", "services"},
     *             @OA\Property(property="carId", type="integer", example=1, description="ID del auto al cual se le crea la cotización"),
     *             @OA\Property(property="mechanicId", type="integer", example=1, description="ID del mecanico que hara la mantencion en caso de aprobar la mantencion"),
     *             @OA\Property(property="services", type="array", description="Lista de servicios solicitados",
     *                 @OA\Items(
     *                     @OA\Property(property="serviceId", type="integer", example=101, description="ID del servicio"),
     *                     @OA\Property(property="isApproved", type="boolean", example=true, description="Si el servicio es aprobado por el cliente")
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=true, description="Estado de la cotización (aprobada o no)"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cotización creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación o de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Service not found")
     *         )
     *     ),
     *
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            if(!auth()->check()) {
                throw new BadHeaderException('not user found');
            }

            $carId = $request->get('carId');
            $userServices = $request->get('services');
            $approvedByClient = $request->get('status');
            $mechanicId = $request->get('mechanicId');
            if (empty($isApprovedDateClient)) {
                $isApprovedDateClient = new Date('now');
            }

            $mechanic = User::whereId($mechanicId)->firstOrFail();
            unset($mechanic->password);
            if (empty($mechanic)) {
                throw new BadHeaderException('mechanic not found');
            }


            $date = now();
            $quotation = new Quotation();
            $quotation->amount_services = count($userServices);
            $quotation->approved_by_client = $approvedByClient;
            $quotation->approve_date_client = $date;
            $quotation->total_price = 0;
            $quotation->car_id = $carId;
            $quotation->is_active = false;
            $quotation->mechanic_id = $mechanic->id;
            $quotation->save();
            $quotationDetails = [];
            $totalPrice = 0;

            foreach ($userServices as $userService) {
                $isApproved = $userService['isApproved'];


                $service = Service::whereId($userService['serviceId'])->first();
                if (!$service) {
                    throw new InvalidArgumentException('Service not found');
                }

                $totalPrice = $totalPrice + $service->price;
                $detail = new QuotationDetails();
                $detail->quotation_id = $quotation->id;
                $detail->service_id = $service->id;
                $detail->is_approved_by_client = $isApproved;
                $detail->save();
                $quotationDetails[] = $detail;
            }
            $quotation->total_price = $totalPrice;
            $quotation->save();
            $success['quotation'] = $quotation;
            $success['details'] = $quotationDetails;

            return $this->sendResponse($success, 'quotation created successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 401);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/quotations/{quotationId}",
     *     summary="Obtiene detalles de una cotización",
     *     description="Devuelve la información de una cotización específica, incluyendo el auto, los servicios aprobados por el cliente y los servicios no aprobados.",
     *     tags={"Quotations"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="quotationId",
     *         in="path",
     *         description="ID de la cotización",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cotización obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="car",
     *                 type="object",
     *                 description="Detalles del auto",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="make", type="string", example="Toyota"),
     *                 @OA\Property(property="model", type="string", example="Corolla"),
     *                 @OA\Property(property="year", type="integer", example=2020)
     *             ),
     *             @OA\Property(
     *                 property="quotation",
     *                 type="object",
     *                 description="Detalles de la cotización",
     *                 @OA\Property(property="id", type="integer", example=100),
     *                 @OA\Property(property="total_price", type="number", format="float", example=1500.75),
     *                 @OA\Property(property="car_id", type="integer", example=1)
     *             ),
     *             @OA\Property(
     *                 property="servicesApprovedByClient",
     *                 type="array",
     *                 description="Servicios aprobados por el cliente",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="name", type="string", example="Cambio de aceite"),
     *                     @OA\Property(property="description", type="string", example="Cambio de aceite del motor")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="servicesNotApprovedByClient",
     *                 type="array",
     *                 description="Servicios no aprobados por el cliente",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=11),
     *                     @OA\Property(property="name", type="string", example="Cambio de neumáticos"),
     *                     @OA\Property(property="description", type="string", example="Cambio de los neumáticos delanteros")
     *                 )
     *             ),
     *             @OA\Property(property="price", type="number", format="float", example=1500.75)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="not user found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function show(Request $request, int $quotationId): JsonResponse
    {
        try {
            if (!auth()->check()) {
                throw new BadHeaderException('not user found');
            }

            $quotation = Quotation::where(['id' => $quotationId])->first();
            $car = Car::whereId($quotation->car_id)->first();
            if (!$car) {
                throw new NoActiveTransaction('not found');
            }

            $details = QuotationDetails::whereQuotationId($quotationId)->get();
            $services = [];
            $servicesNotApprovedByClient = [];
            foreach ($details as $detail) {
                $service = Service::whereId($detail->service_id)->first();
                if($detail->is_approved_by_client) {
                    $services[$service->id] = $service;
                } else {
                    $servicesNotApprovedByClient[$service->id] = $service;
                }
            }

            if ($quotation->mechanic === null) {
                $defaultMechanic = User::whereId($car->mechanic_id)->first();
                unset($defaultMechanic->password);
            } elseif ($quotation->mechanic->id !== $car->mechanic->id) {
                $defaultMechanic = User::whereId($quotation->mechanic->id)->first();
                unset($defaultMechanic->password);
            } else {
                $defaultMechanic = null;
            }



            $response = [
                'car' => [
                    'patent' => $car->patent,
                    'brand' => $this->carHelper->getCarBrandName($car->id),
                    'model' => $this->carHelper->getCarModelName($car->id),
                    'year' => $car->year,
                ],
                'quotation' => $quotation,
                'servicesApprovedByClient' => $services,
                'servicesNotApprovedByClient' => $servicesNotApprovedByClient,
                'total_price' => $quotation->total_price,
                'defaultMechanic' => $defaultMechanic ?? null,
            ];

            $success['quotation'] = $response;

            return $this->sendResponse($success, 'Quotation retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/quotations/",
     *     summary="Obtener todas las cotizaciones de los vehículos del usuario autenticado",
     *     description="Este endpoint recupera todas las cotizaciones asociadas con los vehículos de propiedad del usuario autenticado. Devuelve un array de cotizaciones para cada vehículo.",
     *     tags={"Quotations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cotizaciones recuperadas con éxito.",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado. Usuario no autenticado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error al recuperar las cotizaciones.")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $cars = Car::where(['owner_id' => $user->id])->get();
        $quotations = [];
        foreach ($cars as $car) {
            $allQuotations = Quotation::where(['car_id' => $car->id])->get();
            foreach ($allQuotations as $quotation) {
                $details = QuotationDetails::whereQuotationId($quotation->id)->get();
                $listDetails = [];
                foreach ($details as $detail) {
                    $service = Service::whereId($detail->service_id)->first();
                    $listDetails[] = [
                        'details' => $detail,
                        'service' => $service
                    ];
                }
                $mechanic = User::whereId($quotation->mechanic_id)->first();
                unset($mechanic->password);
                $quotations[] = [
                    'quotation' => $quotation,
                    'content' => $listDetails,
                    'car' => [
                        'patent' => $car->patent,
                        'brand' => $this->carHelper->getCarBrandName($car->id),
                        'model' => $this->carHelper->getCarModelName($car->id),
                        'year' => $car->year
                    ],
                    'mechanic' => $mechanic ?? null
                ];
            }
        }

        $success['quotations'] = $quotations;
        return $this->sendResponse($success, 'Quotation retrieved successfully');
    }

    /**
     * @OA\Patch(
     *     path="/api/jwt/quotations/{quotationId}/approve",
     *     summary="Aprobar una cotización",
     *     description="Este endpoint permite al cliente aprobar una cotización específica utilizando su ID. Al aprobar, se actualiza el estado de la cotización para indicar que ha sido aprobada por el cliente.",
     *     tags={"Quotations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="quotationId",
     *         in="path",
     *         required=true,
     *         description="ID de la cotización que se desea aprobar.",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cotización aprobada con éxito."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cotización no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cotización no encontrada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado. Usuario no autenticado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error al aprobar la cotización.")
     *         )
     *     )
     * )
     */
    public function approve(Request $request, int $quotationId): JsonResponse
    {
        $quotation = Quotation::where(['id' => $quotationId])->first();
        $quotation->approved_by_client = true;
        $quotation->save();

        $success['quotation'] = $quotation;
        return $this->sendResponse($success, 'Quotation approved successfully');
    }
}
