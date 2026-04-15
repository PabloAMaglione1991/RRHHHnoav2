<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;

class PerfilModuloSeeder extends Seeder
{
    public function run()
    {
        Modulo::create([
            'nombre' => 'Mi Perfil',
            'route' => 'mi.perfil', // Debe coincidir con el name de la ruta
            'icon' => 'bi bi-person-circle',
            'activo' => 1,
            'orden' => 99 // Al final
        ]);
    }
}
