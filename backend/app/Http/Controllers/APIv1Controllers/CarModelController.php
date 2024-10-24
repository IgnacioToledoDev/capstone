<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Database\QueryException;
use OpenApi\Annotations as OA;

class CarModelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jwt/cars/models/all",
     *     summary="Obtener todos los modelos de autos",
     *     description="Este endpoint permite obtener una lista de todos los modelos de autos. Requiere autenticación JWT.",
     *     tags={"Car Brands"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Modelos de autos obtenidas exitosamente.",
     *         )
     *     ),
     * )
     */
    public function getAllModels() {
        try {
            $models = CarModel::all();
            $success['models'] = $models;
            return $this->sendResponse($success, 'All models retrieved successfully.');
        } catch (QueryException $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/cars/models/all/{brandId}",
     *     summary="Obtener todos los modelos de autos por marca de auto",
     *     description="Este endpoint permite obtener una lista de todos los modelos de autos por marca. Requiere autenticación JWT.",
     *     tags={"Car Brands"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Modelos de autos obtenidas exitosamente.",
     *         )
     *     ),
     * )
     */
    public function getModelsByBrandId($brandId) {
        $models = CarModel::whereBrandId($brandId)->get();
        $success['models'] = $models;
        return $this->sendResponse($success, 'All models retrieved successfully.');
    }
}
