<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Models\Maintenance;
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
                    ->relationship('status', 'status')
                    ->required(),
                Select::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name')
                    ->required(),
                TextInput::make('recommendation_action')
                    ->label('Recomendacion'),
                TextInput::make('pricing')
                    ->label('Precio')
                    ->required()
                    ->numeric(),
                Select::make('car_id')
                    ->label('Auto')
                    ->relationship('car', 'model')
                    ->required(),
                TextInput::make('mechanic_id')
                    ->label('Mecanico')
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
}
