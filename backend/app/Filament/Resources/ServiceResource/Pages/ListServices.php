<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected static ?string $title = 'Listado';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear servicio'),
        ];
    }
}
