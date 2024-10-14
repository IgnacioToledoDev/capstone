<?php

namespace App\Helper;

use App\Models\CarBrand;

class CarHelper
{

    public function getCarBrandName($carBrandId): string
    {
        $brandName = CarBrand::whereId($carBrandId)->value('name');

        return $brandName ?? 'not found name';
    }
}
