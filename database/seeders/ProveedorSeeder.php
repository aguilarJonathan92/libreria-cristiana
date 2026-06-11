<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'nombre'   => 'Editorial Vida',
                'contacto' => 'Ventas',
                'telefono' => '011-4000-0001',
                'email'    => 'ventas@editorialvida.com',
            ],
            [
                'nombre'   => 'Distribuidora Cristiana Norte',
                'contacto' => 'María González',
                'telefono' => '011-4000-0002',
                'email'    => null,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::firstOrCreate(['nombre' => $proveedor['nombre']], $proveedor);
        }
    }
}
