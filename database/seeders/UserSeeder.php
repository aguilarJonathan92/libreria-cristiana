<?php

namespace Database\Seeders;

use App\Enums\RolUsuario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@libreria.test'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
                'rol'      => RolUsuario::Admin,
            ]
        );

        User::firstOrCreate(
            ['email' => 'vendedor@libreria.test'],
            [
                'name'     => 'Vendedor Demo',
                'password' => Hash::make('password'),
                'rol'      => RolUsuario::Vendedor,
            ]
        );
    }
}