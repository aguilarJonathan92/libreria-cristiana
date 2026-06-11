<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = ['nombre', 'contacto', 'telefono', 'email'];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
