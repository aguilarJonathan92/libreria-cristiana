<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum MetodoPago: string implements HasLabel, HasIcon
{
    case Efectivo       = 'efectivo';
    case Tarjeta        = 'tarjeta';
    case Transferencia  = 'transferencia';
    case CuentaCorriente = 'cuenta_corriente';

    public function getLabel(): ?string
    {
        return match($this) {
            MetodoPago::Efectivo        => 'Efectivo',
            MetodoPago::Tarjeta         => 'Tarjeta',
            MetodoPago::Transferencia   => 'Transferencia',
            MetodoPago::CuentaCorriente => 'Cuenta Corriente',
        };
    }

    public function getIcon(): ?string
    {
        return match($this) {
            MetodoPago::Efectivo        => 'heroicon-o-banknotes',
            MetodoPago::Tarjeta         => 'heroicon-o-credit-card',
            MetodoPago::Transferencia   => 'heroicon-o-arrow-right-circle',
            MetodoPago::CuentaCorriente => 'heroicon-o-user-circle',
        };
    }
}