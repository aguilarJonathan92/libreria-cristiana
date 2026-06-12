<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha_hora')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('monto_entrega_inicial')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('saldo_pendiente')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tipo_venta')
                    ->badge()
                    ->searchable(),
                TextColumn::make('metodo_pago')
                    ->badge()
                    ->searchable(),
                TextColumn::make('usuario.name')
                    ->searchable(),
                TextColumn::make('caja.id')
                    ->searchable(),
                TextColumn::make('cliente.id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
