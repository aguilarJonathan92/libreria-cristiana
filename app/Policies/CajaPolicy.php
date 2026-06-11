<?php

namespace App\Policies;

use App\Models\Caja;
use App\Models\User;

class CajaPolicy
{
    // El historial completo de cajas solo lo ve el admin
    public function viewAny(User $user): bool
    {
        return $user->esAdmin();
    }

    public function view(User $user, Caja $caja): bool
    {
        return $user->esAdmin();
    }

    public function create(User $user): bool
    {
        return true; // ambos roles pueden abrir caja
    }

    public function update(User $user, Caja $caja): bool
    {
        return $user->esAdmin();
    }

    public function delete(User $user, Caja $caja): bool
    {
        return false; // nadie borra cajas
    }
}