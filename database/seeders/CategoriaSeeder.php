<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Biblias',       'descripcion' => 'Biblias en distintas versiones y presentaciones'],
            ['nombre' => 'Libros',        'descripcion' => 'Literatura cristiana, devocionales y teología'],
            ['nombre' => 'Mantillas',     'descripcion' => 'Mantillas y velos para la liturgia'],
            ['nombre' => 'Regalería',     'descripcion' => 'Artículos de regalería y decoración cristiana'],
            ['nombre' => 'Música',        'descripcion' => 'CDs, partituras y materiales musicales'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(['nombre' => $categoria['nombre']], $categoria);
        }
    }
}
