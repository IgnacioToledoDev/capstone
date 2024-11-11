<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CarBrandController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jwt/brands/",
     *     summary="Obtener todas las marcas de autos",
     *     description="Este endpoint permite obtener una lista de todas las marcas de autos. Requiere autenticación JWT.",
     *     tags={"Car Brands"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Marcas de autos obtenidas exitosamente.",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token JWT no encontrado o no válido.",
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
     *                 example="JWT Token no found o is not valid"
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            if(!auth()->check()) {
                return $this->sendError('JWT Token no found o is not valid');
            }

            $brands = CarBrand::all();
            $success['brands'] = $brands;
            return $this->sendResponse($success, 'Brands retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()]);
        }
    }
}
