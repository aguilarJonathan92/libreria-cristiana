<?php

namespace App\Filament\Resources;

use BackedEnum;
use UnitEnum;
use App\Filament\Resources\VentaResource\Pages;
use App\Models\Venta;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationLabel = 'Historial de Ventas';
    protected static ?string $modelLabel = 'Venta';
    protected static ?string $pluralModelLabel = 'Ventas';
    protected static string|UnitEnum|null $navigationGroup = 'Caja';
    protected static ?int $navigationSort = 3;

    // Solo lectura — las ventas no se editan desde acá
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('fecha_hora')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->placeholder('Anónimo')
                    ->searchable(),

                TextColumn::make('tipo_venta')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),

                TextColumn::make('metodo_pago')
                    ->label('Método')
                    ->badge()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable(),

                TextColumn::make('usuario.name')
                    ->label('Vendedor')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipo_venta')
                    ->label('Tipo')
                    ->options(\App\Enums\TipoVenta::class),

                SelectFilter::make('metodo_pago')
                    ->label('Método de pago')
                    ->options(\App\Enums\MetodoPago::class),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentas::route('/'),
        ];
    }
}