<?php

namespace App\Services;

use App\Enums\MetodoPago;
use App\Enums\TipoVenta;
use App\Models\Caja;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class VentaService
{
    /**
     * Crea una venta al contado completa dentro de una transacción.
     * Descuenta stock de cada producto y registra el movimiento en caja.
     *
     * @param array $items  [['producto_id' => 1, 'cantidad' => 2, 'precio_unitario' => 1500.00], ...]
     * @param MetodoPago $metodoPago
     * @param User $usuario
     * @param Caja $caja
     * @param int|null $clienteId  Opcional — para identificar la venta
     * @return Venta
     */
    public function crearVentaContado(
        array $items,
        MetodoPago $metodoPago,
        User $usuario,
        Caja $caja,
        ?int $clienteId = null
    ): Venta {
        return DB::transaction(function () use ($items, $metodoPago, $usuario, $caja, $clienteId) {

            // 1 — Calcular total
            $total = collect($items)->sum(
                fn($item) => $item['cantidad'] * $item['precio_unitario']
            );

            // 2 — Crear cabecera de venta
            $venta = Venta::create([
                'fecha_hora'            => now(),
                'total'                 => $total,
                'monto_entrega_inicial' => $total, // contado = paga todo
                'saldo_pendiente'       => 0,
                'tipo_venta'            => TipoVenta::Contado,
                'metodo_pago'           => $metodoPago,
                'usuario_id'            => $usuario->id,
                'caja_id'               => $caja->id,
                'cliente_id'            => $clienteId,
            ]);

            // 3 — Crear detalles y descontar stock
            foreach ($items as $item) {
                $venta->detalles()->create([
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                ]);

                // Descuento de stock — decrement es atómico en MySQL
                \App\Models\Producto::where('id', $item['producto_id'])
                    ->decrement('stock', $item['cantidad']);
            }

            // 4 — Si hay entrega inicial, registrar como primer pago
            if ($montoEntregaInicial > 0) {
                \App\Models\PagoFinanciacion::create([
                    'venta_id'    => $venta->id,
                    'cliente_id'  => $clienteId,
                    'monto_pagado'=> $montoEntregaInicial,
                    'fecha_pago'  => now(),
                    'notas'       => 'Entrega inicial al momento de la venta',
                    'caja_id'     => $caja->id,
                    // saldo_anterior y saldo_posterior los completa PagoFinanciacionObserver::creating()
                ]);
                // PagoFinanciacionObserver::created() se dispara acá
                // → decrementa saldo_actual del cliente en $montoEntregaInicial
            }
            // Sumar a totales de caja
            $caja->increment('total_ventas', $total);

            return $venta->load('detalles.producto');
        });
    }

    public function crearVentaFinanciada(
        array $items,
        float $montoEntregaInicial,
        int $clienteId,
        User $usuario,
        Caja $caja
    ): Venta {
        return DB::transaction(function () use ($items, $montoEntregaInicial, $clienteId, $usuario, $caja) {

            $total = collect($items)->sum(
                fn($item) => $item['cantidad'] * $item['precio_unitario']
            );

            $saldoPendiente = max(0, $total - $montoEntregaInicial);

            $venta = Venta::create([
                'fecha_hora'            => now(),
                'total'                 => $total,
                'monto_entrega_inicial' => $montoEntregaInicial,
                'saldo_pendiente'       => $saldoPendiente,
                'tipo_venta'            => \App\Enums\TipoVenta::Financiada,
                'metodo_pago'           => \App\Enums\MetodoPago::CuentaCorriente,
                'usuario_id'            => $usuario->id,
                'caja_id'               => $caja->id,
                'cliente_id'            => $clienteId,
            ]);

            foreach ($items as $item) {
                $venta->detalles()->create([
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                ]);

                \App\Models\Producto::where('id', $item['producto_id'])
                    ->decrement('stock', $item['cantidad']);
            }

            if ($montoEntregaInicial > 0) {
                \App\Models\PagoFinanciacion::create([
                    'venta_id'    => $venta->id,
                    'cliente_id'  => $clienteId,
                    'monto_pagado'=> $montoEntregaInicial,
                    'fecha_pago'  => now(),
                    'notas'       => 'Entrega inicial al momento de la venta',
                    'caja_id'     => $caja->id,
                ]);
            }

            // Solo suma el monto efectivamente cobrado hoy (entrega inicial)
            if ($montoEntregaInicial > 0) {
                $caja->increment('total_ventas', $montoEntregaInicial);
            }
            
            return $venta->load('detalles.producto');
        });
    }
}