<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SolicitudLicencia;

class LicenciaService
{
    public function getResumenDescuentosAgente($age_id, $mes, $anio)
    {
        $descuentos = ['lao' => 0, 'escalonado_combinable' => 0, 'total' => 0, 'ninguno' => 0];

        $agetarj = DB::table('t_age_tarj')
            ->where('age_id', $age_id)
            ->where('agetarj_activa', 1)
            ->first();

        if (!$agetarj) return $descuentos;

        $rows = DB::table('t_inasist as i')
            ->join('t_artic as a', 'i.tart_id', '=', 'a.tart_id')
            ->join('t_licencias_tipos as lt', 'a.tart_cod', '=', 'lt.codigo_legacy')
            ->where('i.agetarj_id', $agetarj->agetarj_id)
            ->whereYear('i.inas_fecha', $anio)
            ->whereMonth('i.inas_fecha', $mes)
            ->select('lt.regla_calculo_facturacion', 'lt.grupo_descuento', DB::raw('COUNT(i.inas_id) as total'))
            ->groupBy('lt.regla_calculo_facturacion', 'lt.grupo_descuento')
            ->get();

        $grupos = [];
        foreach ($rows as $r) {
            $regla = $r->regla_calculo_facturacion;
            $grupo = $r->grupo_descuento;
            $dias = (int) $r->total;

            if ($grupo) {
                if (!isset($grupos[$grupo])) $grupos[$grupo] = 0;
                $grupos[$grupo] += $dias;
            }
            if (isset($descuentos[$regla]) && empty($grupo)) {
                $descuentos[$regla] += $dias;
            }
        }

        foreach ($grupos as $g => $t) {
            if (array_key_exists($g, $descuentos)) $descuentos[$g] = $t;
        }

        return $descuentos;
    }

    public function getTiposLicencia()
    {
        return DB::table('t_licencias_tipos')
            ->where('activo', 1)
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public function crearSolicitud(array $data)
    {
        // Validación de Solapamiento
        $overlaps = SolicitudLicencia::where('age_id_agente', $data['age_id_agente'])
            ->whereIn('estado', ['PENDIENTE', 'APROBADA'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('fecha_inicio', [$data['fecha_inicio'], $data['fecha_fin']])
                    ->orWhereBetween('fecha_fin', [$data['fecha_inicio'], $data['fecha_fin']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('fecha_inicio', '<=', $data['fecha_inicio'])
                          ->where('fecha_fin', '>=', $data['fecha_fin']);
                    });
            })
            ->exists();

        if ($overlaps) {
            throw new \Exception('Ya existe una solicitud pendiente o aprobada para este agente en ese periodo.');
        }

        return SolicitudLicencia::create($data);
    }

    public function getMisSolicitudes($age_id)
    {
        return SolicitudLicencia::with('tipoLicencia')
            ->where('age_id_agente', $age_id) 
            ->orderBy('fecha_solicitud', 'desc')
            ->get();
    }

    public function aprobarSolicitud($solicitudId)
    {
        DB::transaction(function () use ($solicitudId) {
            $sol = SolicitudLicencia::findOrFail($solicitudId);
            $sol->update(['estado' => 'APROBADA']);

            AuditService::log('APROBAR_LICENCIA', 'SolicitudLicencia', $solicitudId, [
                'agente' => $sol->age_id_agente,
                'inicio' => $sol->fecha_inicio,
                'fin' => $sol->fecha_fin
            ]);

            $agetarj = DB::table('t_age_tarj')
                ->where('age_id', $sol->age_id_agente)
                ->where('agetarj_activa', 1)
                ->first();

            if ($agetarj) {
                // CORREGIDO: Usamos 'id' en lugar de 'lt_id'
                $lt = DB::table('t_licencias_tipos')->where('id', $sol->licencia_tipo_id)->first();
                $artic = DB::table('t_artic')->where('tart_cod', $lt->codigo_legacy)->first();

                if ($artic) {
                    $start = Carbon::parse($sol->fecha_inicio);
                    $end = Carbon::parse($sol->fecha_fin);
                    $inasistencias = [];

                    while ($start->lte($end)) {
                        $inasistencias[] = [
                            'agetarj_id' => $agetarj->agetarj_id,
                            'tart_id' => $artic->tart_id,
                            'inas_fecha' => $start->format('Y-m-d')
                        ];
                        $start->addDay();
                    }

                    if (!empty($inasistencias)) {
                        DB::table('t_inasist')->insert($inasistencias);
                    }
                }
            }
        });
    }

    public function rechazarSolicitud($solicitudId, $motivo)
    {
        $sol = SolicitudLicencia::findOrFail($solicitudId);
        $sol->update(['estado' => 'RECHAZADA', 'motivo_rechazo' => $motivo]);

        AuditService::log('RECHAZAR_LICENCIA', 'SolicitudLicencia', $solicitudId, ['motivo' => $motivo]);
    }
}
