<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\FichadaService;
use App\Models\Agente;
use Illuminate\Support\Facades\DB;

class MigrationTest extends TestCase
{
    /**
     * Test DB Connection and Fichada Logic
     *
     * @return void
     */
    public function test_db_connection_and_logic()
    {
        // 1. Verify DB Connection
        try {
            DB::connection()->getPdo();
            $this->assertTrue(true, "Database connection successful");
        } catch (\Exception $e) {
            $this->fail("Database connection failed: " . $e->getMessage());
        }

        // 2. Verify Data Access
        $agente = Agente::first();
        if (!$agente) {
            $this->markTestSkipped('No agents found in t_agente table.');
        }
        $this->assertNotNull($agente->age_id, "Agente has ID");

        // 3. Verify Logic Calculation
        $service = new FichadaService();
        $mes = date('m');
        $anio = date('Y');

        // Simular mes anterior si es dia 1
        if (date('d') < 2) {
            $mes = date('m', strtotime('-1 month'));
            $anio = date('Y', strtotime('-1 month'));
        }

        $horas = $service->getHorasTrabajadasMes($agente->age_id, $mes, $anio);

        // El resultado debe ser entero o float, y >= 0
        $this->assertIsNumeric($horas);
        $this->assertGreaterThanOrEqual(0, $horas);

        echo "\n\n   >>> VERIFICACION EXITOSA <<<\n";
        echo "   Agente ID: " . $agente->age_id . "\n";
        echo "   Horas Calculadas ($mes/$anio): " . $horas . " minutos\n";
    }
}
