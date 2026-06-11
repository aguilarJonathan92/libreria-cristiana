<?php

namespace App\Filament\Resources\Cajas;

use App\Filament\Resources\Cajas\Pages;
use App\Models\Caja;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CajaResource extends Resource
{
    protected static ?string $model = Caja::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Historial de Cajas';
    protected static ?string $modelLabel = 'Caja';
    protected static ?string $pluralModelLabel = 'Cajas';
    protected static UnitEnum|string|null $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->esAdmin() ?? false;
    }

    // Solo lectura — sin crear ni editar desde aquí
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]); // no se usa, solo tabla
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('usuario.name')
                    ->label('Abierta por')
                    ->sortable(),

                TextColumn::make('fecha_apertura')
                    ->label('Apertura')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('fecha_cierre')
                    ->label('Cierre')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Abierta')
                    ->sortable(),

                TextColumn::make('monto_inicial')
                    ->label('Inicial')
                    ->money('ARS'),

                TextColumn::make('monto_final')
                    ->label('Final')
                    ->money('ARS')
                    ->placeholder('—'),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCajas::route('/'),
        ];
    }
}