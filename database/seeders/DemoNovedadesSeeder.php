<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Novedad;
use Carbon\Carbon;

class DemoNovedadesSeeder extends Seeder
{
    public function run()
    {
        // Limpiar novedades anteriores para visualización limpia
        Novedad::truncate();

        Novedad::create([
            'titulo' => 'Jornada de Enfermería 2026',
            'contenido' => 'Invitamos a todo el personal al ciclo de capacitación continua que se realizará en el Auditorio Central el día 15 de Enero a las 09:00 hs. Se entregarán certificados de asistencia.',
            'tipo' => 'info',
            'fecha_publicacion' => Carbon::now()
        ]);

        Novedad::create([
            'titulo' => 'INTERRUPCIÓN ELÉCTRICA PROGRAMADA',
            'contenido' => 'ATENCIÓN: Se realizará un corte de energía en el Pabellón B el día Domingo de 14:00 a 16:00 hs por mantenimiento de tableros. Por favor tomar recaudos con equipos sensibles.',
            'tipo' => 'danger',
            'fecha_publicacion' => Carbon::now()->subDay()
        ]);

        Novedad::create([
            'titulo' => 'Actualización de Protocolos de Seguridad',
            'contenido' => 'Se encuentran disponibles los nuevos protocolos de acceso y seguridad física. Es obligatorio leer la documentación en la intranet antes del viernes.',
            'tipo' => 'warning',
            'fecha_publicacion' => Carbon::now()->subDays(2)
        ]);
    }
}
