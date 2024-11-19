<?php

namespace App\Helper;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;

class CarHelper
{

    public function getCarBrandName($carBrandId): string
    {
        $car = Car::whereId($carBrandId)->first();
        $brandName = CarBrand::whereId($car->brand_id)->first();
        return $brandName->name ?? 'not found name';
    }

    public function getCarModelName($carModelId): string
    {
        $car = Car::whereId($carModelId)->first();
        $modelName = CarModel::where(['id'=> $car->model_id])->first()->name;
        return $modelName ?? 'not found name';
    }

    public function getFullName(int $carId): string
    {
        $car = Car::whereId($carId)->first();
        $brandName = CarBrand::whereId($car->brand_id)->first()->name;
        $modelName = CarModel::whereId($car->model_id)->first()->name;

        return $brandName. ' ' .  $modelName. ' ' .  $car->year;
    }
}
