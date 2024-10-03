<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\Select::make('brand_id')
                    ->label('Marca')
                    ->relationship('carBrands', 'name')
                    ->placeholder('Seleccione una opcion')
                    ->required(),
                Forms\Components\TextInput::make('model')
                    ->label('Modelo')
                    ->placeholder('Modelo')
                    ->required(),
                Forms\Components\Select::make('year')
                    ->label('Año')
                    ->options(array_combine($years, $years))
                    ->required(),
                Forms\Components\Select::make('userId')
                    ->label('Propietario')
                    ->relationship('user', 'name')
                    ->placeholder('Propietario')
                    ->options(self::getCustomerUser())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('carBrands.name')
                    ->searchable()
                    ->label('Marca'),
                Tables\Columns\TextColumn::make('model')
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
        $clients = [];
        $users = User::all();
        foreach ($users as $user) {
            $userRole = $user->getRoleNames();
            if ($userRole[0] === User::CLIENT) {
                $clients[] = $user->name;
            }
        }

        return $clients ?? [];
    }
}
