<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Helper\CarHelper;
use App\Helper\UserHelper;
use App\Models\Car;
use App\Models\Maintenance;
use App\Utils\Constants;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'gmdi-car-repair';

    protected static ?string $navigationLabel = 'Mantenciones';

    protected static ?string $modelLabel = 'Mantención';

    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('description')
                    ->label('Descripcion')
                    ->required(),
                Select::make('status_id')
                    ->label('Estado')
                    ->placeholder(Constants::SELECT_OPTION)
                    ->relationship('status', 'status')
                    ->required(),
                Select::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name')
                    ->required()
                    ->placeholder(Constants::SELECT_OPTION),
                TextInput::make('recommendation_action')
                    ->label('Recomendacion'),
                TextInput::make('pricing')
                    ->label('Precio')
                    ->required()
                    ->numeric(),
                Select::make('car_id')
                    ->label('Auto')
                    ->options(self::getFullNameCars())
                    ->placeholder(Constants::SELECT_OPTION)
                    ->required(),
                Select::make('mechanic_id')
                    ->label('Mecanico')
                    ->options(self::getMechanics())
                    ->placeholder(Constants::SELECT_OPTION)
                    ->required(),
                DatePicker::make('start_maintenance')
                    ->label('Fecha Inicio Mantenimiento'),
                DatePicker::make('end_maintenance')
                    ->label('Fecha Fin Mantenimiento'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }

    protected static function getFullNameCars(): array {
        $carHelper = new CarHelper();
        $userHelper = new UserHelper();
        $cars = Car::all();
        $options = [];
        foreach ($cars as $car) {
            $brandName = $carHelper->getCarBrandName($car->id);
            $userFullName = $userHelper->getFullName($car->owner_id);
            $options[$car->id] = $brandName . ' ' .
                $car->brand . ' ' .
                $car->model . ' ' .
                $car->year . ' de ' .
                $userFullName
            ;
        }

        return $options;
    }

    protected static function getMechanics(): array {
        $userHelper = new UserHelper();
        return $userHelper->getMechanicUsers();
    }
}
