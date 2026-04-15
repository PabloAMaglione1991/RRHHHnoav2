<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Agente;
use App\Models\SolicitudLicencia;
use App\Services\LicenciaService;
use Carbon\Carbon;

class LicenciaTest extends TestCase
{
    // Usamos RefreshDatabase para vaciar la DB entre tests (OJO: requiere configuración de DB_CONNECTION=sqlite en phpunit.xml)
    // use RefreshDatabase; 

    public function test_no_se_puede_crear_licencia_solapada()
    {
        $service = new LicenciaService();
        $agenteId = 1; // Usamos un ID que sepamos que existe o mockeamos

        // 1. Crear una licencia inicial
        $data1 = [
            'age_id_agente' => $agenteId,
            'licencia_tipo_id' => 1,
            'fecha_solicitud' => '2026-05-01',
            'fecha_inicio' => '2026-06-01',
            'fecha_fin' => '2026-06-10',
            'motivo' => 'Licencia Inicial',
            'estado' => 'APROBADA'
        ];
        
        // Mocking Eloquent if DB is not available for tests
        // Aquí asumimos que corre contra una DB de test o local
        SolicitudLicencia::create($data1);

        // 2. Intentar crear una que se solapa (inicio dentro del rango)
        $data2 = [
            'age_id_agente' => $agenteId,
            'licencia_tipo_id' => 1,
            'fecha_solicitud' => '2026-05-02',
            'fecha_inicio' => '2026-06-05',
            'fecha_fin' => '2026-06-15',
            'motivo' => 'Licencia Solapada',
            'estado' => 'PENDIENTE'
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe una solicitud pendiente o aprobada para este agente en ese periodo.');

        $service->crearSolicitud($data2);
    }
}
