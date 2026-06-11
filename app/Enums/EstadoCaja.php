<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum EstadoCaja: string implements HasLabel, HasColor
{
    case Abierta = 'abierta';
    case Cerrada = 'cerrada';

    public function getLabel(): ?string
    {
        return match($this) {
            EstadoCaja::Abierta => 'Abierta',
            EstadoCaja::Cerrada => 'Cerrada',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            EstadoCaja::Abierta => 'success',
            EstadoCaja::Cerrada => 'danger',
        };
    }
}