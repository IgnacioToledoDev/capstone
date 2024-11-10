<?php

namespace App\Filament\Resources\CarModelResource\Pages;

use App\Filament\Resources\CarModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarModels extends ListRecords
{
    protected static string $resource = CarModelResource::class;

    protected static ?string $title = 'Listado';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Agregar modelo'),
        ];
    }
}
