<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeesResource::class;

    protected static ?string $title = 'Listado';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear Empleado'),
        ];
    }
}
