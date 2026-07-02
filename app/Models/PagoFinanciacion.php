<?php

namespace App\Models;

use App\Enums\MetodoPago;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoFinanciacion extends Model
{
    protected $table = 'pago_financiaciones';
    
    protected $fillable = [
        'monto_pagado',
        'saldo_anterior',
        'saldo_posterior',
        'fecha_pago',
        'metodo_pago',
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
        'metodo_pago'     => MetodoPago::class, 
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