<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Enums\MetodoPago;
use App\Enums\TipoVenta;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('fecha_hora')
                    ->required(),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('monto_entrega_inicial')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('saldo_pendiente')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('tipo_venta')
                    ->options(TipoVenta::class)
                    ->default('contado')
                    ->required(),
                Select::make('metodo_pago')
                    ->options(MetodoPago::class)
                    ->default('efectivo')
                    ->required(),
                Select::make('usuario_id')
                    ->relationship('usuario', 'name')
                    ->required(),
                Select::make('caja_id')
                    ->relationship('caja', 'id')
                    ->required(),
                Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->default(null),
            ]);
    }
}
