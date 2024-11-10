<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\User;
use App\Rules\ValidateRut;
use App\Utils\Constants;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rawilk\FilamentPasswordInput\Password;

class EmployeesResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?string $navigationLabel = 'Empleados';

    protected static ?string $modelLabel = 'Empleado';

    protected static ?string $navigationGroup = 'Administración';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function ($query) {
            $query->whereIn('name', [User::MECHANIC, User::COMPANY_ADMIN]);
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->label('Nombre del usuario')
                    ->required()
                    ->placeholder('Nombre del usuario'),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->placeholder('Nombre'),
                Forms\Components\TextInput::make('lastname')
                    ->label('Apellido')
                    ->placeholder('Apellido'),
                Forms\Components\TextInput::make('email')
                    ->label('Correo electrónico')
                    ->required()
                    ->email()
                    ->placeholder('Correo electrónico del usuario'),
                Forms\Components\TextInput::make('rut')
                    ->label('Rut')
                    ->placeholder('Rut')
                    ->required()
                    ->rules([
                        new ValidateRut(),
                    ])
                    ->helperText('Ingrese rut sin puntos y con guion'),
                Password::make('password')
                    ->required()
                    ->label('Contraseña')
                    ->default('')
                    ->visible(fn () => auth()->user()->hasRole([User::SUPER_ADMIN,
                        User::COMPANY_ADMIN]))
                    ->placeholder('Contraseña del usuario'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name', function ($query) {
                        $query->where('guard_name', 'api');
                    })
                    ->label('Roles')
                    ->required()
                    ->placeholder(Constants::SELECT_OPTION),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefono')
                    ->numeric()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rut')
                    ->label('Rut')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployees::route('/create'),
            'edit' => Pages\EditEmployees::route('/{record}/edit'),
        ];
    }
}
