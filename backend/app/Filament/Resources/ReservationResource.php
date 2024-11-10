<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Helper\CarHelper;
use App\Helper\UserHelper;
use App\Models\Car;
use App\Models\Reservation;
use App\Utils\Constants;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Reservaciones';

    protected static ?string $modelLabel = 'Reservacion';

    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\TextColumn::make('date_reservation')
                    ->label('Fecha de reservacion'),
                TextColumn::make('is_approved_by_mechanic')
                    ->label('Aprobado por mecanico'),
                TextColumn::make('has_reminder')
                    ->label('Recordatorio')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    protected static function getFullNameCars(): array
    {
        $carHelper = new CarHelper();
        $userHelper = new UserHelper();
        $cars = Car::all();
        $options = [];
        foreach ($cars as $car) {
            $brandName = $carHelper->getCarBrandName($car->id);
            $modelName = $carHelper->getCarModelName($car->model_id);
            $userFullName = $userHelper->getFullName($car->owner_id);
            $options[$car->id] = $brandName . ' ' .
                $car->brand . ' ' .
                $car->$modelName . ' ' .
                $car->year . ' de ' .
                $userFullName;
        }

        return $options;
    }
}
