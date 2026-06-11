<?php

namespace App\Filament\Resources\Cajas\Schemas;

use App\Enums\EstadoCaja;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CajaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('fecha_apertura')
                    ->required(),
                DateTimePicker::make('fecha_cierre'),
                TextInput::make('monto_inicial')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('monto_final')
                    ->numeric()
                    ->default(null),
                Select::make('estado')
                    ->options(EstadoCaja::class)
                    ->default('abierta')
                    ->required(),
                Select::make('usuario_id')
                    ->relationship('usuario', 'name')
                    ->required(),
            ]);
    }
}
