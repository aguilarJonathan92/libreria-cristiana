<?php

namespace App\Services;

use App\Models\Caja;
use App\Models\PagoFinanciacion;
use App\Models\Venta;
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
        ?string $notas = null
    ): PagoFinanciacion {
        return DB::transaction(function () use ($venta, $monto, $caja, $notas) {

            if ($monto <= 0) {
                throw new \InvalidArgumentException('El monto del pago debe ser mayor a cero.');
            }

            if ($monto > $venta->saldo_pendiente) {
                throw new \InvalidArgumentException(
                    "El monto ($monto) supera el saldo pendiente ({$venta->saldo_pendiente})."
                );
            }

            $pago = PagoFinanciacion::create([
                'venta_id'    => $venta->id,
                'cliente_id'  => $venta->cliente_id,
                'monto_pagado'=> $monto,
                'fecha_pago'  => now(),
                'notas'       => $notas,
                'caja_id'     => $caja->id,
                // saldo_anterior y saldo_posterior los completa el Observer
            ]);

            return $pago;
        });
    }
}