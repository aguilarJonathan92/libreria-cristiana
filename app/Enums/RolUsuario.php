<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RolUsuario: string implements HasLabel
{
    case Admin    = 'admin';
    case Vendedor = 'vendedor';

    public function getLabel(): ?string
    {
        return match($this) {
            RolUsuario::Admin    => 'Administrador',
            RolUsuario::Vendedor => 'Vendedor',
        };
    }
}