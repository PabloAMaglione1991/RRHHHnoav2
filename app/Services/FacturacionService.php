<?php

namespace App\Services;

use App\Models\Agente;
use App\Models\Inasistencia; // Asumo que crearemos este modelo
use App\Models\Fichada;
use Carbon\Carbon;

class FacturacionService
{
    /**
     * Calcula el estado de facturación para todos los agentes elegibles en un mes dado.
     */
    public function calcularDistribucion($mes, $anio)
    {
        // 1. Obtener Agentes Elegibles
        // Regla: Activos, Muros = INTRA, Registran Horario
        $agentes = Agente::where('age_activo', 1)
            ->where('muros', 'INTRA')
            //->where('no_registra_horario', 0) // Validar si el campo es 0 o false en BD
            ->with(['ageTarj', 'departamento'])
            ->get();

        $resultado = [];

        foreach ($agentes as $agente) {
            // Filtrar si no registra horario (doble check por si el campo es null)
            if ($agente->no_registra_horario) {
                continue;
            }

            $detalle = $this->calcularAgente($agente, $mes, $anio);
            $resultado[] = $detalle;
        }

        return $resultado;
    }

    /**
     * Calcula el porcentaje de cobro para un agente específico.
     */
    public function calcularAgente($agente, $mes, $anio)
    {
        $fechaInicio = Carbon::createFromDate($anio, $mes, 1);
        $fechaFin = $fechaInicio->copy()->endOfMonth();

        // 2. Buscar Inasistencias / Artículos en el periodo
        // Necesitamos mapear Inasistencias con Artículos
        // Asumimos modelo Inasistencia mapeado a t_inasist
        // Y relación con t_age_tarj

        $inasistencias = \Illuminate\Support\Facades\DB::table('t_inasist')
            ->join('t_age_tarj', 't_inasist.agetarj_id', '=', 't_age_tarj.agetarj_id')
            ->join('t_artic', 't_inasist.tart_id', '=', 't_artic.tart_id')
            ->where('t_age_tarj.age_id', $agente->age_id)
            ->whereBetween('t_inasist.inas_fecha', [$fechaInicio->format('Y-m-d'), $fechaFin->format('Y-m-d')])
            ->select('t_artic.tart_cod', 't_inasist.inas_fecha')
            ->get();

        $dias14A = 0;
        $descuento = 0;
        $motivos = [];

        foreach ($inasistencias as $ina) {
            // Lógica de descuentos (Simplificada inicial)
            // 14A: Corta duración
            if ($ina->tart_cod == '14A') { // Asumir código 14A existe
                $dias14A++;
            }
            // Otros códigos de descuento directo (Ejemplo 100%)
            elseif (in_array($ina->tart_cod, ['AUS', 'SUS', 'PAR'])) {
                $descuento = 100;
                $motivos[] = "$ina->tart_cod (100%)";
            }
        }

        // Aplicar regla 14A
        // 2 días -> 25%, 3 días -> 50%, >3 días -> 100%
        if ($dias14A > 0) {
            if ($dias14A == 2) {
                if ($descuento < 25) {
                    $descuento = 25;
                    $motivos[] = "14A x $dias14A (25%)";
                }
            } elseif ($dias14A == 3) {
                if ($descuento < 50) {
                    $descuento = 50;
                    $motivos[] = "14A x $dias14A (50%)";
                }
            } elseif ($dias14A > 3) {
                $descuento = 100;
                $motivos[] = "14A x $dias14A (100%)";
            }
        }

        // Lógica final
        $porcentajeCobro = max(0, 100 - $descuento);

        return [
            'agente' => $agente,
            'dias_14a' => $dias14A,
            'descuento_aplicado' => $descuento,
            'porcentaje_cobro' => $porcentajeCobro,
            'motivos' => implode(', ', $motivos)
        ];
    }
}
