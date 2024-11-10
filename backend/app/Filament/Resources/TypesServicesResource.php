<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypesServicesResource\Pages;
use App\Filament\Resources\TypesServicesResource\RelationManagers;
use App\Models\TypeService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class TypesServicesResource extends Resource
{
    protected static ?string $model = TypeService::class;

    protected static ?string $navigationLabel = 'Tipos de servicios';

    protected static ?string $modelLabel = 'Tipo de servicio';

    protected static ?string $navigationGroup = 'Servicios';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label('Nombre'),
                Textarea::make('description')
                ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Identificador'),
                Tables\Columns\TextColumn::make('name')
                ->label('Nombre')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('description')
                ->label('Descripcion'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListTypesServices::route('/'),
        ];
    }
}
