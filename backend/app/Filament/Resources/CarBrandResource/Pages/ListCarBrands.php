<?php

namespace App\Filament\Resources\CarBrandResource\Pages;

use App\Filament\Resources\CarBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarBrands extends ListRecords
{
    protected static string $resource = CarBrandResource::class;

    protected static ?string $title = 'Listado';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Agregar marca'),
        ];
    }
}
