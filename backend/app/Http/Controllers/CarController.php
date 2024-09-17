<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarBrand;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
