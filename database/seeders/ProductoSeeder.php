<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $catBiblias   = Categoria::where('nombre', 'Biblias')->first()->id;
        $catLibros    = Categoria::where('nombre', 'Libros')->first()->id;
        $catMantillas = Categoria::where('nombre', 'Mantillas')->first()->id;
        $provVida     = Proveedor::where('nombre', 'Editorial Vida')->first()->id;

        $productos = [
            [
                'sku'           => 'BIB-RV60-TDURA-NEG-MED',
                'nombre'        => 'Biblia Reina Valera 1960 - Tapa Dura Negra Mediana',
                'nombre_familia' => 'Biblia Reina Valera 1960',
                'editorial'     => 'Editorial Vida',
                'atributos'     => ['tapa' => 'dura', 'color' => 'negro', 'tamaño' => 'mediano'],
                'precio_costo'  => 8500,
                'precio_venta'  => 15000,
                'stock'         => 12,
                'stock_minimo'  => 3,
                'categoria_id'  => $catBiblias,
                'proveedor_id'  => $provVida,
            ],
            [
                'sku'           => 'BIB-RV60-TBLAN-BLAN-MED',
                'nombre'        => 'Biblia Reina Valera 1960 - Tapa Blanda Blanca Mediana',
                'nombre_familia' => 'Biblia Reina Valera 1960',
                'editorial'     => 'Editorial Vida',
                'atributos'     => ['tapa' => 'blanda', 'color' => 'blanco', 'tamaño' => 'mediano'],
                'precio_costo'  => 6000,
                'precio_venta'  => 11000,
                'stock'         => 8,
                'stock_minimo'  => 3,
                'categoria_id'  => $catBiblias,
                'proveedor_id'  => $provVida,
            ],
            [
                'sku'           => 'BIB-NVI-TDURA-VINO-GRD',
                'nombre'        => 'Biblia NVI - Tapa Dura Vino Grande',
                'nombre_familia' => 'Biblia Nueva Versión Internacional',
                'editorial'     => 'Editorial Vida',
                'atributos'     => ['tapa' => 'dura', 'color' => 'vino', 'tamaño' => 'grande'],
                'precio_costo'  => 10000,
                'precio_venta'  => 18000,
                'stock'         => 2,
                'stock_minimo'  => 3,
                'categoria_id'  => $catBiblias,
                'proveedor_id'  => $provVida,
            ],
            [
                'sku'           => 'LIB-PROPFIN-001',
                'nombre'        => 'El Propósito de Todo - Rick Warren',
                'autor'         => 'Rick Warren',
                'editorial'     => 'Editorial Vida',
                'precio_costo'  => 4000,
                'precio_venta'  => 7500,
                'stock'         => 5,
                'stock_minimo'  => 2,
                'categoria_id'  => $catLibros,
                'proveedor_id'  => $provVida,
            ],
            [
                'sku'           => 'MAN-ENCAJE-BLAN-MED',
                'nombre'        => 'Mantilla de Encaje Blanca Mediana',
                'nombre_familia' => 'Mantillas de Encaje',
                'atributos'     => ['material' => 'encaje', 'color' => 'blanco', 'tamaño' => 'mediano'],
                'precio_costo'  => 2000,
                'precio_venta'  => 4000,
                'stock'         => 20,
                'stock_minimo'  => 5,
                'categoria_id'  => $catMantillas,
                'proveedor_id'  => null,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::firstOrCreate(['sku' => $producto['sku']], $producto);
        }
    }
}
