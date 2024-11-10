<?php

namespace App\Filament\Resources\TypesServicesResource\Pages;

use App\Filament\Resources\TypesServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypesServices extends ListRecords
{
    protected static string $resource = TypesServicesResource::class;

    protected static ?string $title = 'Listado';


    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
