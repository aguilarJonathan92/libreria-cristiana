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

            return $venta->load('detalles.producto');
        });
    }
}