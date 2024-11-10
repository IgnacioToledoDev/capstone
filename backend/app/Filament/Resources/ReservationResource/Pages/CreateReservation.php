<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        if ($data['has_reminder']) {
            $reminder = Reminder::create([
                'is_sending' => $data['is_sending'] ?? false,
                'contact_type_id' => $data['contact_type_id'],
            ]);

            $this->record->update([
                'reminder_id' => $reminder->id,
            ]);
        }
    }
}
