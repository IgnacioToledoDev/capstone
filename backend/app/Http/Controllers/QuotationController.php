<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Service;
use Doctrine\DBAL\Exception\NoActiveTransaction;
use http\Exception\BadHeaderException;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/quotations",
     *     summary="Crear una cotización",
     *     description="Este endpoint crea una nueva cotización para un auto, incluyendo los servicios solicitados y su estado de aprobación.",
     *     tags={"Cotizaciones"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear una cotización",
     *         @OA\JsonContent(
     *             required={"carId", "services"},
     *             @OA\Property(property="carId", type="integer", example=1, description="ID del auto al cual se le crea la cotización"),
     *             @OA\Property(property="services", type="array", description="Lista de servicios solicitados",
     *                 @OA\Items(
     *                     @OA\Property(property="serviceId", type="integer", example=101, description="ID del servicio"),
     *                     @OA\Property(property="isApproved", type="boolean", example=true, description="Si el servicio es aprobado por el cliente")
     *                 )
     *             ),
     *             @OA\Property(property="status", type="integer", example=true, description="Estado de la cotización (aprobada o no)"),
     *             @OA\Property(property="approvedDateClient", type="string", format="date", example="2024-10-23", description="Fecha en la que el cliente aprobó la cotización")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cotización creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="quotation", ref="#/components/schemas/Quotation"),
     *             @OA\Property(property="message", type="string", example="Quotation created"),
     *             @OA\Property(property="details", ref="#/components/schemas/QuotationDetails")
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
            $status = $request->get('status');
            $approvedDateClient = $request->get('approvedDateClient');
            $defaultStatus = 0;
            if ($status || !empty($approvedDateClient)) {
                $defaultStatus = 1;
            }
            $quotation = new Quotation();
            $quotation->amount_services = count($userServices);
            $quotation->status = $defaultStatus;
            $quotation->approve_date_client = $approvedDateClient ?? null;
            $quotation->car_id = $carId;
            $quotation->save();
            $totalPrice = 0;
            foreach ($userServices as $userService) {
                $isApproved = $userService['isApproved'];
                if(!$quotation->exists) {
                    throw new InvalidArgumentException('Quotation no created');
                }

                $service = Service::whereId($userService['serviceId'])->first();
                if (!$service) {
                    throw new InvalidArgumentException('Service not found');
                }

                $totalPrice = $totalPrice + $service->price;
                $detail = new QuotationDetails();
                $detail->quotation_id = $quotation->id;
                $detail->service_id = $service->id;
                $detail->is_approved_by_client = $isApproved;
            }

            $quotation->total_price = $totalPrice;
            $quotation->save();
            $success['quotation'] = $quotation;
            $success['message'] = "Quotation created";
            $success['details'] = $detail;

            return $this->sendResponse($success, $success['message']);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 401);
        }
    }

    /**
     * @OA\Get(
     *     path="/quotations/{quotationId}",
     *     summary="Obtener detalles de una cotización",
     *     description="Este endpoint permite obtener los detalles de una cotización para un auto, junto con los servicios aprobados y no aprobados por el cliente.",
     *     tags={"Cotizaciones"},
     *
     *     @OA\Parameter(
     *         name="quotationId",
     *         in="path",
     *         description="ID de la cotización",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="carId",
     *         in="query",
     *         description="ID del auto asociado a la cotización",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cotización obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="quotation",
     *                 @OA\Property(property="car", ref="#/components/schemas/Car"),
     *                 @OA\Property(property="quotation", ref="#/components/schemas/Quotation"),
     *                 @OA\Property(property="servicesApprovedByClient", type="array", description="Servicios aprobados por el cliente",
     *                     @OA\Items(ref="#/components/schemas/Service")
     *                 ),
     *                 @OA\Property(property="servicesNotApprovedByClient", type="array", description="Servicios no aprobados por el cliente",
     *                     @OA\Items(ref="#/components/schemas/Service")
     *                 ),
     *                 @OA\Property(property="price", type="integer", example=1500, description="Precio total de la cotización")
     *             ),
     *             @OA\Property(property="message", type="string", example="Quotation retrieved successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación o de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="not user found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el auto o la cotización",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="not found")
     *         )
     *     ),
     *
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(Request $request, int $quotationId): JsonResponse
    {
        try {
            if (!auth()->check()) {
                throw new BadHeaderException('not user found');
            }

            $carId = $request->get('carId');
            $car = Car::whereId($carId)->first();
            if (!$car) {
                throw new NoActiveTransaction('not found');
            }

            $quotation = Quotation::where(['car_id' => $carId])->last();
            $details = QuotationDetails::whereQuotationId($quotationId)->first();
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

            $response = [
                'car' => $car,
                'quotation' => $quotation,
                'servicesApprovedByClient' => $services,
                'servicesNotApprovedByClient' => $servicesNotApprovedByClient,
                'price' => $quotation->total_price
            ];

            $success['quotation'] = $response;

            return $this->sendResponse($success, 'Quotation retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuotationController $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuotationController $quotation)
    {
        //
    }
}
