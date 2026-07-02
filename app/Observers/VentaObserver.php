<?php

namespace App\Observers;

use App\Enums\TipoVenta;
use App\Models\Venta;

class VentaObserver
{
    /**
     * Al crear una venta financiada, incrementar el saldo del cliente
     * por el monto pendiente (total - entrega inicial).
     */
    public function created(Venta $venta): void
    {
        if ($venta->tipo_venta !== TipoVenta::Financiada) return;
        if (!$venta->cliente_id) return;
        if ($venta->saldo_pendiente <= 0) return;

        $venta->cliente()->increment('saldo_actual', $venta->saldo_pendiente);
    }

    /**
     * Si se elimina una venta financiada (anulación futura),
     * revertir el saldo del cliente.
     */
    public function deleted(Venta $venta): void
    {
        if ($venta->tipo_venta !== TipoVenta::Financiada) return;
        if (!$venta->cliente_id) return;
        if ($venta->saldo_pendiente <= 0) return;

        $venta->cliente()->decrement('saldo_actual', $venta->saldo_pendiente);
    }
}