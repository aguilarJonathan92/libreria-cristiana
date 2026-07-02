<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoFinanciacion extends Model
{
    protected $fillable = [
        'monto_pagado',
        'saldo_anterior',
        'saldo_posterior',
        'fecha_pago',
        'notas',
        'venta_id',
        'cliente_id',
        'caja_id',
    ];

    protected $casts = [
        'monto_pagado'   => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_posterior'=> 'decimal:2',
        'fecha_pago'     => 'datetime',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }
}