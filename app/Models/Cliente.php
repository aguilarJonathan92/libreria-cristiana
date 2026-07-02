<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'saldo_actual',
        'limite_credito',
    ];

    protected $casts = [
        'saldo_actual'   => 'decimal:2',
        'limite_credito' => 'decimal:2',
    ];

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function pagosFinanciacion(): HasMany
    {
        return $this->hasMany(PagoFinanciacion::class);
    }

    // ─── Helpers de dominio ──────────────────────────────────────────

    public function tieneDeuda(): bool
    {
        return $this->saldo_actual > 0;
    }

    public function superaLimiteCredito(float $montoNuevo): bool
    {
        if ($this->limite_credito <= 0) return false; // sin límite definido
        return ($this->saldo_actual + $montoNuevo) > $this->limite_credito;
    }

    public function creditoDisponible(): float
    {
        if ($this->limite_credito <= 0) return 0;
        return max(0, $this->limite_credito - $this->saldo_actual);
    }
}