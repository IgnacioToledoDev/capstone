<?php

namespace App\Http\Controllers\APIv1Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarBrand;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CarController extends Controller
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
     *             @OA\Property(property="model", type="string", example="Toyota Corolla", description="Model of the car"),
     *             @OA\Property(property="year", type="integer", example=2024, description="Year of the car"),
     *             @OA\Property(property="user_id", type="integer", example=1, description="ID of the user who owns the car")
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
     *                 @OA\Property(property="model", type="string", example="Toyota Corolla"),
     *                 @OA\Property(property="year", type="integer", example=2024),
     *                 @OA\Property(property="user_id", type="integer", example=1),
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
                'model' => 'required|string',
                'year' => 'required|integer|min:' . Car::MIN_YEAR . '|max:' . $this->getMaxYear(),
                'patent' => 'required|string'
            ]);

            $brand = CarBrand::find($validated['brand_id'])->get();

            if (!$brand) {
                return $this->sendError('Error');
            }

            $car = new Car();
            $car->brand_id = $validated['brand_id'];
            $car->model = $validated['model'];
            $car->year = $validated['year'];
            $car->user_id = $user->id;
            $car->save();
            $success['car'] = $car;

            return $this->sendResponse($success, 'Car created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cars/{patent}",
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
     *
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
        if (!$car) {
            return $this->sendError('Car not found');
        }

        $success['car'] = $car;

        return $this->sendResponse($success, 'Car retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
}
