<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Service;
use App\Models\StatusCar;
use App\Models\TypeService;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Exception;
use OpenApi\Annotations as OA;
use function Laravel\Prompts\form;

class ServiceController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/jwt/services/",
     *     summary="Obtener todos los servicios",
     *     description="Este endpoint permite obtener una lista de todos los servicios. Requiere autenticación JWT.",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Servicios obtenidos exitosamente.",
     *         )
     *     ),
     * )
     */
    public function index()
    {
        try {
            if (!auth()->check()) {
                return $this->sendError('JWT not authenticated.', 401);
            }

            $services = Service::all();
            $success['services'] = $services;

            return $this->sendResponse($success, 'Services retrieved successfully.');
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return $this->sendError('JWT not authenticated.', 401);
        }

        $carId = $request->get('carId');
        $notes = $request->get('notes');
        $services = $request->get('services');
        $typeService = $request->get('typeService');
        $mechanicId = $request->get('mechanicId');
        $mileage = $request->get('mileage');
        $listServices = json_decode($services, true);
        $error = [];
        $totalPricing = 0;

        foreach ($listServices as $service) {
            $serviceFound = Service::where(['id' => $service['id']])->first();
            if(!$serviceFound){
                $error = $serviceFound;
            }

            $totalPricing += $serviceFound['pricing'];
        }

        if(!empty($error)) {
            $this->sendError('service not found');
        }

        $mechanic = User::whereId($mechanicId)->first();
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
    public function show(ServiceController $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceController $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceController $service)
    {
        //
    }

    private function generateName($typeServiceId): string {
        $typeService = TypeService::where(['id' => $typeServiceId])->first();
        $now = new \DateTime('now');

        return 'new ' . $typeService->name . $now->format('d-m-Y');
    }
}
