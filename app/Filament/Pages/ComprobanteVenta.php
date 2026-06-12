<?php

namespace App\Filament\Pages;

use BackedEnum;
use App\Models\Venta;
use Filament\Pages\Page;

class ComprobanteVenta extends Page
{
    protected static BackedEnum|string|null $navigationIcon = null;
    protected static bool $shouldRegisterNavigation = false; // No aparece en el menú
    protected string $view = 'filament.pages.comprobante-venta';

    protected static ?string $slug = 'comprobante-venta/{venta}';

    public ?Venta $venta = null;

    public function mount(Venta $venta): void
    {
         $this->venta = $venta->load([
            'detalles.producto',
            'usuario',
            'cliente',
        ]);     
    }
}