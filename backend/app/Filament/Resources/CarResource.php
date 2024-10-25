<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Helper\UserHelper;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use App\Utils\Constants;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-s-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Vehiculos';

    protected static ?string $modelLabel = 'Vehiculo';

    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        $currentYear = date('Y');
        $years = range($currentYear, 1990);

        return $form
            ->schema([
                Forms\Components\TextInput::make('patent')
                    ->label('Patente')
                    ->required(),
                Forms\Components\Select::make('brand_id')
                    ->label('Marca')
                    ->relationship('carBrands', 'name')
                    ->placeholder(Constants::SELECT_OPTION)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (string $state, callable $set) {
                        $set('model_id', null);
                    }),
                Forms\Components\Select::make('model_id')
                    ->label('Modelo')
                    ->options(function (Get $get) {
                        return CarModel::query()
                            ->where('brand_id', $get('brand_id'))
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->placeholder(Constants::SELECT_OPTION)
                    ->required()
                    ->live()
                    ->disabled(fn (Get $get): bool => !$get('brand_id')),
                Forms\Components\Select::make('year')
                    ->label('Año')
                    ->placeholder(Constants::SELECT_OPTION )
                    ->options(array_combine($years, $years))
                    ->required(),
                Forms\Components\Select::make('owner_id')
                    ->label('Propietario')
                    ->placeholder(Constants::SELECT_OPTION)
                    ->options(self::getCustomerUser()),
                Forms\Components\Select::make('mechanic_id')
                    ->label('Mecanico')
                    ->placeholder(Constants::SELECT_OPTION)
                    ->options(self::getMechanicUsers()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patent')
                    ->searchable()
                    ->default('N/A')
                    ->label('Patente'),
                Tables\Columns\TextColumn::make('carBrands.name')
                    ->searchable()
                    ->label('Marca'),
                Tables\Columns\TextColumn::make('carModels.name')
                    ->searchable()
                    ->label('Modelo'),
                Tables\Columns\TextColumn::make('year')
                    ->searchable()
                    ->label('Año'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Propietario'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Borrar')
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }

    protected static function getCustomerUser(): array
    {
        $userHelper = new UserHelper();
        return $userHelper->getCustomerUsers();
    }

    protected static function getMechanicUsers(): array
    {
        $userHelper = new UserHelper();
        return $userHelper->getMechanicUsers();
    }

    protected static function getModelByBrand($brandId): array
    {
        $models = CarModel::where('brand_id', $brandId)->get();
        foreach ($models as $model) {
            $modelByBrand[$model->id] = $model->name;
        }

        return $modelByBrand ?? [];
    }
}
