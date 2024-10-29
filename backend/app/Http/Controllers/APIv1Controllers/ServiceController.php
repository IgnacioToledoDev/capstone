<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\TypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;
use OpenApi\Annotations as OA;

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
    public function index(): JsonResponse
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
     * @OA\Get(
     *     path="/api/jwt/services/types",
     *     summary="Obtener todos los nombres de los tipos de servicios",
     *     description="Este endpoint permite obtener una lista de todos los nombres de los tipos servicios. Requiere autenticación JWT.",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Nombre de los tipos servicios obtenidos exitosamente.",
     *         )
     *     ),
     * )
     */
    public function getTypeServiceName(): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return $this->sendError('JWT not authenticated.', 401);
            }

            $types = TypeService::all();
            $serviceNameList = [];
            foreach ($types as $type) {
                $serviceNameList[] = [
                    'id' => $type->id,
                    'name' => $type->name
                ];
            }

            $success['types'] = $serviceNameList;

            return $this->sendResponse($success, 'Types retrieved successfully.');
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

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
}
