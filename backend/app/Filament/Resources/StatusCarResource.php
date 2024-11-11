<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusCarResource\Pages;
use App\Filament\Resources\StatusCarResource\RelationManagers;
use App\Models\StatusCar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusCarResource extends Resource
{
    protected static ?string $model = StatusCar::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Estados';

    protected static ?string $modelLabel = 'Estados';

    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Identificador'),
                Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('description')
                ->searchable()
                ->sortable(),
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
            'index' => Pages\ListStatusCars::route('/'),
            'create' => Pages\CreateStatusCar::route('/create'),
            'edit' => Pages\EditStatusCar::route('/{record}/edit'),
        ];
    }
}
