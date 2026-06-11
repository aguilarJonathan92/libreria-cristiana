<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum TipoVenta: string implements HasLabel, HasColor
{
    case Contado    = 'contado';
    case Financiada = 'financiada';

    public function getLabel(): ?string
    {
        return match($this) {
            TipoVenta::Contado    => 'Contado',
            TipoVenta::Financiada => 'Financiada',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            TipoVenta::Contado    => 'success',
            TipoVenta::Financiada => 'warning',
        };
    }
}