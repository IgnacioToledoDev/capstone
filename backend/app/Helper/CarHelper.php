<?php

namespace App\Helper;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;

class CarHelper
{

    public function getCarBrandName($carId): string
    {
        $car = Car::whereId($carId)->first();
        if (!$car && !isset($car->brand_id)) {
            $brand = CarBrand::whereId($carId)->first();
            return $brand->name;
        }

        $brandName = CarBrand::whereId($car->brand_id)->first();
        return $brandName->name ?? 'not found name';
    }

    public function getCarModelName($carId): string
    {
        $car = Car::whereId($carId)->first();
        if (!$car && !isset($car->model_id)) {
            $brand = CarModel::whereId($car->model_id)->first();
            return $brand->name;
        }

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
