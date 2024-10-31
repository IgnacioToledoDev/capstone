<?php

namespace App\Helper;

use App\Models\Service;

class ServiceHelper
{
    public function getServiceName($serviceId): string {
        $service = Service::whereId($serviceId)->first();
        return $service->name ?? '';
    }
}
