<?php

namespace App\Filament\Resources\TypesServicesResource\Pages;

use App\Filament\Resources\TypesServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypesServices extends EditRecord
{
    protected static string $resource = TypesServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
