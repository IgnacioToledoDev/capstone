<?php

namespace App\Filament\Resources\StatusCarResource\Pages;

use App\Filament\Resources\StatusCarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusCars extends ListRecords
{
    protected static string $resource = StatusCarResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
