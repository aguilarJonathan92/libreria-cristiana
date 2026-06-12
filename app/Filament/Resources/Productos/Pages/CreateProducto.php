<?php

namespace App\Filament\Resources\Productos\Pages;

use App\Filament\Resources\Productos\ProductoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProducto extends CreateRecord
{
    // Vincula esta página al recurso principal que ya creaste
    protected static string $resource = ProductoResource::class;

    /**
     * Opcional: Redirecciona al listado de productos después de crear uno.
     * Si prefieres que se quede en la página de edición, puedes borrar este método.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Opcional: Cambiar el título de la pestaña/página
     */
    public function getTitle(): string
    {
        return 'Crear Nuevo Producto';
    }
}