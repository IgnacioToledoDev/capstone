<?php

namespace App\Filament\Resources\CarModelResource\Pages;

use App\Filament\Resources\CarModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarModel extends EditRecord
{
    protected static string $resource = CarModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
