<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarBrandResource\Pages;
use App\Filament\Resources\CarBrandResource\RelationManagers;
use App\Models\CarBrand;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class CarBrandResource extends Resource
{
    protected static ?string $model = CarBrand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Marcas';

    protected static ?string $modelLabel = 'Marca';

    protected static ?string $navigationGroup = 'Automoviles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre marca')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Identificador'),
                TextColumn::make('name')
                ->label('Nombre marca')
                ->searchable()
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
            'index' => Pages\ListCarBrands::route('/'),
            'create' => Pages\CreateCarBrand::route('/create'),
            'edit' => Pages\EditCarBrand::route('/{record}/edit'),
        ];
    }
}
