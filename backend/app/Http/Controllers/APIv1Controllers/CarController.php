<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Helper\CarHelper;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CarController extends Controller
{
    private CarHelper $carHelper;
    public function __construct(CarHelper $carHelper)
    {
        $this->carHelper = $carHelper;
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/cars",
     *     summary="Obtener lista de autos del usuario autenticado",
     *     description="Este endpoint devuelve la lista de autos pertenecientes al usuario autenticado.",
     *     tags={"Cars"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de autos recuperada exitosamente",
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
     *                     property="cars",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="patent", type="string", example="ABC123"),
     *                         @OA\Property(property="brand", type="string", example="Toyota"),
     *                         @OA\Property(property="model", type="string", example="Corolla"),
     *                         @OA\Property(property="year", type="integer", example=2015)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Cars retrieved successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $cars = Car::whereOwnerId($user->id)->get();
        $allCars = [];
        foreach ($cars as $car) {
            $carObj = [
                'id' => $car->id,
                'patent' => $car->patent,
                'brand' => $this->carHelper->getCarBrandName($car->id),
                'model' => $this->carHelper->getCarModelName($car->id),
                'year' => $car->year,
            ];
            $allCars[] = $carObj;
        }

        $success['cars'] = $allCars;

        return $this->sendResponse($success, 'Cars retrieved successfully.');
    }

    /**
     * @OA\Post(
     *     path="/api/jwt/cars/create",
     *     summary="Create a new car",
     *     tags={"Cars"},
     *     security={{
     *         "bearerAuth": {}
     *     }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_id", "model", "year", "user_id"},
     *             @OA\Property(property="brand_id", type="integer", example=1, description="ID of the car brand"),
     *             @OA\Property(property="model_id", type="integer", example="1", description="Model of the car"),
     *             @OA\Property(property="patent", type="string", example="kbtd92", description="Patent of the car"),
     *             @OA\Property(property="owner_id", type="integer", example="1", description="Id of the owner"),
     *             @OA\Property(property="year", type="integer", example=2024, description="Year of the car"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Car created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="car", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="brand_id", type="integer", example=1),
     *                 @OA\Property(property="model_id", type="integer", example="1"),
     *                 @OA\Property(property="year", type="integer", example=2024),
     *                 @OA\Property(property="owner_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-17T02:42:18Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-17T02:42:18Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation Error."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error.")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            if(!auth()->check()) {
                return $this->sendError('JWT Token no found o is not valid');
            }
            $user = auth()->user();

            $validated = $request->validate([
                'brand_id' => 'required|integer',
                'model_id' => 'required|integer',
                'year' => 'required|integer|min:' . Car::MIN_YEAR . '|max:' . $this->getMaxYear(),
                'patent' => 'required|string',
                'owner_id' => 'required|integer',
            ]);

            $brand = CarBrand::find($validated['brand_id'])->get();
            $model = CarModel::find($validated['model_id'])->get();
            $owner = User::whereId($validated['owner_id'])->first();
            if (!$brand && !$owner && !$model) {
                return $this->sendError('Error');
            }

            $car = new Car();
            $car->brand_id = $validated['brand_id'];
            $car->model_id = $validated['model_id'];
            $car->year = $validated['year'];
            $car->patent = $validated['patent'];
            $car->owner_id = $validated['owner_id'];
            $car->save();
            $success['car'] = $car;
            // todo if the car by the patent has old owner to change

            return $this->sendResponse($success, 'Car created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/cars/{patent}",
     *     tags={"Cars"},
     *     summary="Retrieve car by patent",
     *     description="Returns car details based on the patent provided.",
     *     operationId="getCarByPatent",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="patent",
     *         in="path",
     *         description="Patent of the car to be retrieved",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="ABC123"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Car retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Car retrieved successfully."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Car not found",
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
     *                 example="Car not found"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="JWT Token not found or is not valid",
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
     *                 example="JWT Token not found or is not valid"
     *             )
     *         )
     *     )
     * )
     */
    public function show(string $patent): JsonResponse
    {
        if (!auth()->check()) {
            return $this->sendError('JWT Token no found o is not valid');
        }

        $car = Car::wherePatent($patent)->first();
        $owner = User::whereId($car->owner_id)->first();
        if (!$car && !$owner) {
            return $this->sendError('Car or client not found');
        }

        $success['car'] = [
            'car' => $car,
            'owner' => $owner,
        ];

        return $this->sendResponse($success, 'Car retrieved successfully.');
    }

    private function getMaxYear(): int
    {
        $year = (new DateTime())->format('Y');
        $month = (new DateTime())->format('m');

        if ($month === 9) {
            return $year + 1;
        }

        return $year;
    }

    /**
     * @OA\Get(
     *     path="/api/jwt/cars/{userId}/all",
     *     summary="Obtener vehículos por ID de usuario",
     *     description="Este endpoint obtiene todos los vehículos asociados a un usuario específico.",
     *     tags={"Cars"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario para obtener sus vehículos",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículos obtenidos exitosamente",
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
     *                     property="cars",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="patent", type="string", example="ABC123"),
     *                         @OA\Property(property="brand", type="string", example="Toyota"),
     *                         @OA\Property(property="model", type="string", example="Corolla"),
     *                         @OA\Property(property="year", type="integer", example=2020)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="JWT Token no encontrado o inválido",
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
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
     *                 example="Usuario no encontrado"
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
     *                 example="Ocurrió un error al obtener los vehículos"
     *             )
     *         )
     *     )
     * )
     */
    public function getCarsByUserId(int $userId): JsonResponse
    {
        if (!auth()->check()) {
            return $this->sendError('JWT Token no found o is not valid');
        }
        $carsList = [];

        $cars = Car::whereOwnerId($userId)->get();
        foreach ($cars as $car) {
            $carObj = [
                'id' => $car->id,
                'patent' => $car->patent,
                'brand' => $this->carHelper->getCarBrandName($car->id),
                'model' => $this->carHelper->getCarModelName($car->id),
                'year' => $car->year,
            ];
            $carsList[] = $carObj;
        }

        $success['cars'] = $carsList;
        return $this->sendResponse($success, 'Cars retrieved successfully.');
    }
}
