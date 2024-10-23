<?php

namespace App\Helper;

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
        $modelName = CarModel::where(['id'=> $carModelId])->value('name');
        return $modelName ?? 'not found name';
    }
}
