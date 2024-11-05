<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactType;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ContactTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jwt/contactTypes",
     *     summary="Obtener todos los tipos de contacto",
     *     tags={"Contacto"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\Response(
     *          response=200,
     *          description="Tipos de contacto recuperados exitosamente.",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="contactTypes", type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="name", type="string", example="Teléfono")
     *                      )
     *                  )
     *              ),
     *              @OA\Property(property="message", type="string", example="retrieved contact types successfully.")
     *          )
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="Error en la solicitud.",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Error message"),
     *              @OA\Property(property="code", type="integer", example=400)
     *          )
     *      )
     * )
     */
    public function getAllContactTypes(): JsonResponse
    {
        try {
            $contactTypes = ContactType::all();
            $success['contactTypes'] = $contactTypes;

            return $this->sendResponse($success, 'retrieved contact types successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getCode());
        }
    }
}
