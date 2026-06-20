<?php

namespace App\Filament\Resources\Ventas;

use App\Enums\MetodoPago;
use App\Filament\Resources\Ventas\Pages;
use App\Models\Venta;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Historial de Ventas';
    protected static UnitEnum|string|null $navigationGroup = 'Administración';
    protected static ?string $modelLabel = 'Venta';
    protected static ?string $pluralModelLabel = 'Ventas';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->prefix('#'),

                TextColumn::make('fecha_hora')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->default('Venta anónima')
                    ->searchable(),

                TextColumn::make('metodo_pago')
                    ->label('Método de pago')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state) => match ($state) {
                        MetodoPago::Efectivo       => 'success',
                        MetodoPago::Tarjeta        => 'info',
                        MetodoPago::Transferencia  => 'warning',
                        default                    => 'gray',
                    }),

                TextColumn::make('detalles_count')
                    ->label('Ítems')
                    ->counts('detalles')
                    ->alignCenter(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('usuario.name')
                    ->label('Vendedor')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('fecha_hora', 'desc')
            ->filters([
                SelectFilter::make('metodo_pago')
                    ->label('Método de pago')
                    ->options(MetodoPago::class),

                Filter::make('fecha')
                    ->label('Rango de fechas')
                    ->form([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query
                            ->when($data['desde'], fn ($q) => $q->whereDate('fecha_hora', '>=', $data['desde']))
                            ->when($data['hasta'], fn ($q) => $q->whereDate('fecha_hora', '<=', $data['hasta']));
                    }),
            ])
            ->actions([
                ViewAction::make()->label('Ver detalle'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentas::route('/'),
            'view'  => Pages\ViewVenta::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}