<?php

namespace App\Models;

use App\Enums\MetodoPago;
use App\Enums\TipoVenta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $fillable = [
        'fecha_hora',
        'total',
        'monto_entrega_inicial',
        'saldo_pendiente',
        'tipo_venta',
        'metodo_pago',
        'usuario_id',
        'caja_id',
        'cliente_id',
    ];

    protected $casts = [
        'fecha_hora'            => 'datetime',
        'total'                 => 'decimal:2',
        'monto_entrega_inicial' => 'decimal:2',
        'saldo_pendiente'       => 'decimal:2',
        'tipo_venta'            => TipoVenta::class,
        'metodo_pago'           => MetodoPago::class,
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function pagosFinanciacion(): HasMany
    {
        return $this->hasMany(PagoFinanciacion::class);
    }

    // Helper: cuánto se ha pagado en total de esta venta
    public function totalPagado(): float
    {
        return (float) $this->pagosFinanciacion()->sum('monto_pagado');
    }

    // Helper: si la deuda de esta venta está saldada
    public function estaSaldada(): bool
    {
        return $this->saldo_pendiente <= 0;
    }
}