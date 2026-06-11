<?php

namespace App\Models;

use App\Enums\RolUsuario;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'rol'               => RolUsuario::class,
    ];

    // ─── Filament: control de acceso al panel ───────────────────────
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->rol, [RolUsuario::Admin, RolUsuario::Vendedor]);
    }

    // ─── Helpers de dominio ──────────────────────────────────────────
    public function esAdmin(): bool
    {
        return $this->rol === RolUsuario::Admin;
    }

    public function esVendedor(): bool
    {
        return $this->rol === RolUsuario::Vendedor;
    }

    // ─── Relaciones ──────────────────────────────────────────────────
    public function cajas(): HasMany
    {
        return $this->hasMany(Caja::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }
}