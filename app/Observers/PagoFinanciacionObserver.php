<?php

namespace App\Observers;

use App\Models\PagoFinanciacion;
use App\Models\Venta;

class PagoFinanciacionObserver
{
    /**
     * Al registrar un pago:
     * 1. Decrementar saldo_actual del cliente
     * 2. Decrementar saldo_pendiente de la venta correspondiente
     */
    public function creating(PagoFinanciacion $pago): void
    {
        // Poblar saldo_anterior y saldo_posterior antes de guardar
        $cliente = $pago->cliente;
        $pago->saldo_anterior  = $cliente->saldo_actual;
        $pago->saldo_posterior = max(0, $cliente->saldo_actual - $pago->monto_pagado);
    }

    public function created(PagoFinanciacion $pago): void
    {
        // Actualizar saldo del cliente
        $pago->cliente()->decrement('saldo_actual', $pago->monto_pagado);

        // Actualizar saldo pendiente de la venta
        Venta::where('id', $pago->venta_id)
            ->decrement('saldo_pendiente', $pago->monto_pagado);
    }
}