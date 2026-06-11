<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('codigo_barras')
                    ->default(null),
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('nombre_familia')
                    ->default(null),
                TextInput::make('autor')
                    ->default(null),
                TextInput::make('editorial')
                    ->default(null),
                Textarea::make('atributos')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('precio_costo')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('precio_venta')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('stock_minimo')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('categoria_id')
                    ->required()
                    ->numeric(),
                TextInput::make('proveedor_id')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
