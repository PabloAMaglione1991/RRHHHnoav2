<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Obtiene la cantidad de personas que ficharon cada día en los últimos X días.
     */
    public function getTendenciaAsistencia($dias = 7)
    {
        $startDate = Carbon::now()->subDays($dias - 1)->startOfDay();
        
        $stats = DB::table('t_fichadas')
            ->where('fich_fecha', '>=', $startDate)
            ->select(DB::raw('DATE(fich_fecha) as fecha'), DB::raw('COUNT(DISTINCT tarj_nro) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        // Rellenar huecos si no hay fichadas algunos días
        $labels = [];
        $data = [];
        for ($i = $dias - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $found = $stats->firstWhere('fecha', $date);
            
            $labels[] = Carbon::parse($date)->format('d/m');
            $data[] = $found ? $found->total : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Obtiene la distribución de tipos de licencias para el mes actual.
     */
    public function getDistribucionLicenciasMes()
    {
        $mes = date('m');
        $anio = date('Y');

        $stats = DB::table('t_inasist as i')
            ->join('t_artic as a', 'i.tart_id', '=', 'a.tart_id')
            ->join('t_licencias_tipos as lt', 'a.tart_cod', '=', 'lt.codigo_legacy')
            ->whereYear('i.inas_fecha', $anio)
            ->whereMonth('i.inas_fecha', $mes)
            ->select('lt.nombre', DB::raw('COUNT(i.inas_id) as total'))
            ->groupBy('lt.nombre')
            ->get();

        return [
            'labels' => $stats->pluck('nombre')->toArray(),
            'data' => $stats->pluck('total')->toArray()
        ];
    }
}
