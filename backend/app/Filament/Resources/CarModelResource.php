<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarModelResource\Pages;
use App\Filament\Resources\CarModelResource\RelationManagers;
use App\Models\CarModel;
use App\Utils\Constants;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarModelResource extends Resource
{
    protected static ?string $model = CarModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Modelos';

    protected static ?string $modelLabel = 'Modelo';

    protected static ?string $navigationGroup = 'Automoviles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->placeholder(Constants::SELECT_OPTION),
                Select::make('brand_id')
                    ->label('Marca')
                    ->placeholder(Constants::SELECT_OPTION)
                    ->required()
                    ->relationship('brand', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('identificador'),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->label('Marca')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
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
            'index' => Pages\ListCarModels::route('/'),
            'create' => Pages\CreateCarModel::route('/create'),
            'edit' => Pages\EditCarModel::route('/{record}/edit'),
        ];
    }
}
