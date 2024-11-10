<?php

namespace App\Filament\Resources\StatusCarResource\Pages;

use App\Filament\Resources\StatusCarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatusCar extends EditRecord
{
    protected static string $resource = StatusCarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
