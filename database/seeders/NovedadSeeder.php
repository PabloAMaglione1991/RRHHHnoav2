<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Novedad;
use Carbon\Carbon;

class NovedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Novedad::create([
            'titulo' => 'Campaña de Vacunación Antigripal',
            'contenido' => 'Se informa a todo el personal que a partir del próximo lunes comienza la campaña anual de vacunación.',
            'tipo' => 'info',
            'fecha_publicacion' => Carbon::now()->subDays(2)
        ]);

        Novedad::create([
            'titulo' => 'Mantenimiento del Sistema',
            'contenido' => 'El sistema de Historias Clínicas estará fuera de servicio este sábado de 22:00 a 06:00 por mantenimiento programado.',
            'tipo' => 'warning',
            'fecha_publicacion' => Carbon::now()->subDays(1)
        ]);

        Novedad::create([
            'titulo' => 'Nuevo Protocolo COVID-19',
            'contenido' => 'Se ha actualizado el protocolo de atención ambulatoria. Por favor, revisar la documentación en la intranet.',
            'tipo' => 'danger',
            'fecha_publicacion' => Carbon::now()
        ]);
    }
}
