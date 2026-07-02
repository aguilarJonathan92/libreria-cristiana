<?php

namespace App\Models;

use App\Enums\EstadoCaja;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caja extends Model
{
    protected $fillable = [
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_final',
        'diferencia',
        'total_ventas',
    'total_cobros_financiacion',
        'estado',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre'   => 'datetime',
        'monto_inicial'  => 'decimal:2',
        'monto_final'    => 'decimal:2',
        'total_ventas'   => 'decimal:2',
    'total_cobros_financiacion'  => 'decimal:2',
        'estado'         => EstadoCaja::class,
    ];

    // ─── Relaciones ──────────────────────────────────────────────────
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function pagosFinanciacion(): HasMany
    {
        return $this->hasMany(PagoFinanciacion::class);
    }

    // ─── Helpers de dominio ──────────────────────────────────────────
    public function estaAbierta(): bool
    {
        return $this->estado === EstadoCaja::Abierta;
    }

    // ─── Scopes ──────────────────────────────────────────────────────
    public function scopeAbierta($query)
    {
        return $query->where('estado', EstadoCaja::Abierta->value);
    }

    public function totalDelDia(): float
    {
        return (float) $this->monto_inicial
            + (float) $this->total_ventas
            + (float) $this->total_cobros_financiacion;
    }
}