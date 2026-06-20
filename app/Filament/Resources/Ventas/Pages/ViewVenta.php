<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Ventas\VentaResource;
use App\Enums\MetodoPago;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Support\Enums\FontWeight;

class ViewVenta extends ViewRecord
{
    protected static string $resource = VentaResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Información de la venta')
                    ->icon('heroicon-o-receipt-percent')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('id')
                            ->label('Número de venta')
                            ->prefix('#')
                            ->weight(FontWeight::Bold),

                        TextEntry::make('fecha_hora')
                            ->label('Fecha y hora')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('metodo_pago')
                            ->label('Método de pago')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state->getLabel())
                            ->color(fn ($state) => match ($state) {
                                MetodoPago::Efectivo        => 'success',
                                MetodoPago::Tarjeta         => 'info',
                                MetodoPago::Transferencia   => 'warning',
                                MetodoPago::CuentaCorriente => 'purple',
                                default                     => 'gray',
                            }),

                        TextEntry::make('cliente.nombre')
                            ->label('Cliente')
                            ->default('Venta anónima'),

                        TextEntry::make('usuario.name')
                            ->label('Vendedor'),

                        TextEntry::make('caja.fecha_apertura')
                            ->label('Caja')
                            ->dateTime('d/m/Y H:i')
                            ->prefix('Turno del '),
                    ]),

                Section::make('Productos')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        RepeatableEntry::make('detalles')
                            ->label('')
                            ->schema([
                                TextEntry::make('producto.nombre')
                                    ->label('Producto')
                                    ->weight(FontWeight::Medium),

                                TextEntry::make('cantidad')
                                    ->label('Cantidad')
                                    ->alignCenter(),

                                TextEntry::make('precio_unitario')
                                    ->label('Precio unitario')
                                    ->money('ARS'),

                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('ARS')
                                    ->weight(FontWeight::Bold)
                                    ->getStateUsing(fn ($record) => $record->subtotal()),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Totales')
                    ->icon('heroicon-o-calculator')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('total')
                            ->label('Total de la venta')
                            ->money('ARS')
                            ->size('lg')
                            ->weight(FontWeight::Bold)
                            ->color('success'),

                        TextEntry::make('monto_entrega_inicial')
                            ->label('Monto entregado')
                            ->money('ARS'),

                        TextEntry::make('saldo_pendiente')
                            ->label('Saldo pendiente')
                            ->money('ARS')
                            ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                    ]),
            ]);
    }
}