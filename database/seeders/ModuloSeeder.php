<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuloSeeder extends Seeder
{
    public function run()
    {
        $modulos = [
            [
                'nombre' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'bi bi-speedometer2',
                'activo' => 1,
                'orden' => 10
            ],
            [
                'nombre' => 'Gestión Usuarios',
                'route' => 'gestion.usuarios',
                'icon' => 'bi bi-people',
                'activo' => 1,
                'orden' => 20
            ],
            [
                'nombre' => 'Mis Fichadas',
                'route' => 'mis.fichadas',
                'icon' => 'bi bi-calendar-check',
                'activo' => 1,
                'orden' => 30
            ],
            [
                'nombre' => 'Gestión Horarios',
                'route' => 'gestion.horarios',
                'icon' => 'bi bi-clock',
                'activo' => 1,
                'orden' => 40
            ],
            [
                'nombre' => 'Solicitar Licencias',
                'route' => 'mis.licencias',
                'icon' => 'bi bi-file-earmark-medical',
                'activo' => 1,
                'orden' => 50
            ],
            [
                'nombre' => 'Aprobar Licencias',
                'route' => 'gestionar.licencias',
                'icon' => 'bi bi-check-circle',
                'activo' => 1,
                'orden' => 60
            ],
            [
                'nombre' => 'Tipos de Licencias',
                'route' => 'gestion.tipos.licencias',
                'icon' => 'bi bi-tags',
                'activo' => 1,
                'orden' => 65
            ],
            [
                'nombre' => 'Reportes',
                'route' => 'reportes',
                'icon' => 'bi bi-bar-chart-line',
                'activo' => 1,
                'orden' => 70
            ],
        ];

        DB::table('t_modulos')->insert($modulos);
    }
}
