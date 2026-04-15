<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;

class NovedadesModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Modulo::create([
            'nombre' => 'Gestión Novedades',
            'route' => 'gestion-novedades',
            'icon' => 'bi bi-megaphone',
            'activo' => true,
            'orden' => 6
        ]);
    }
}
