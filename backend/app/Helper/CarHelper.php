<?php

namespace App\Helper;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;

class CarHelper
{

    public function getCarBrandName($carBrandId): string
    {
        $brandName = CarBrand::whereId($carBrandId)->value('name');
        return $brandName ?? 'not found name';
    }

    public function getCarModelName($carModelId): string
    {
        $modelName = CarModel::where(['id'=> $carModelId])->first()->name;
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
