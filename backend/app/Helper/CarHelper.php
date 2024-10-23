<?php

namespace App\Helper;

use App\Models\CarBrand;
use App\Models\CarModel;

class CarHelper
{

    public function getCarBrandName($carBrandId): string
    {
        $brandName = CarBrand::whereId($carBrandId)->value('name');
        CarModel::whereId($carBrandId)->update(['name' => $brandName]);

        return $brandName ?? 'not found name';
    }
}
