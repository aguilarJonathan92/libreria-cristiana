<?php

namespace App\Services;

use App\Models\Caja;
use App\Models\PagoFinanciacion;
use App\Models\Venta;
use App\Enums\MetodoPago;
use Illuminate\Support\Facades\DB;

class PagoService
{
    /**
     * Registra un pago parcial o total de una venta financiada.
     *
     * @param Venta  $venta       La venta a la que se aplica el pago
     * @param float  $monto       Monto a pagar (no puede superar saldo_pendiente)
     * @param Caja   $caja        Caja activa del día
     * @param string|null $notas  Observaciones opcionales
     * @return PagoFinanciacion
     */
    public function registrarPago(
        Venta $venta,
        float $monto,
        Caja $caja,
        MetodoPago|string $metodoPago = MetodoPago::Efectivo,  // ← nuevo
        ?string $notas = null
    ): PagoFinanciacion {
        return DB::transaction(function () use ($venta, $monto, $caja, $metodoPago, $notas) {

            if ($monto <= 0) {
                throw new \InvalidArgumentException('El monto del pago debe ser mayor a cero.');
            }

            if ($monto > $venta->saldo_pendiente) {
                throw new \InvalidArgumentException(
                    "El monto ($monto) supera el saldo pendiente ({$venta->saldo_pendiente})."
                );
            }

            return PagoFinanciacion::create([
                'venta_id'     => $venta->id,
                'cliente_id'   => $venta->cliente_id,
                'monto_pagado' => $monto,
                'metodo_pago'  => $metodoPago,  // ← nuevo
                'fecha_pago'   => now(),
                'notas'        => $notas,
                'caja_id'      => $caja->id,
            ]);
        });
    }
}