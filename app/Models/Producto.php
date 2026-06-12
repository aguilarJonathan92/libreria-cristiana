<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Producto extends Model
{
    protected $fillable = [
        'sku',
        'codigo_barras',
        'nombre',
        'nombre_familia',
        'autor',
        'editorial',
        'atributos',
        'precio_costo',
        'precio_venta',
        'stock',
        'stock_minimo',
        'categoria_id',
        'proveedor_id',
    ];

    protected $casts = [
        'atributos'    => 'array',
        'precio_costo' => 'decimal:2',
        'precio_venta' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function tieneStockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}
