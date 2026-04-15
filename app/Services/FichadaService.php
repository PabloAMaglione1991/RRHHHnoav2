<?php

namespace App\Services;

use App\Models\Agente;
use Illuminate\Support\Facades\DB;
use PDO;

class FichadaService
{
    /**
     * Calcula horas trabajadas en el mes (Lógica portada de helpers.php)
     */
    public function getHorasTrabajadasMes($age_id, $mes, $anio)
    {
        // 1. Obtener datos de horario y tarjeta
        // Usamos DB raw o modelos. Para ser fiel a la lógica original que usa joins específicos:
        $horario = DB::table('t_agente as a')
            ->leftJoin('t_horarios_definiciones as h', 'a.horario_definicion_id', '=', 'h.id')
            ->leftJoin('t_age_tarj as att', 'a.age_id', '=', 'att.age_id')
            ->where('a.age_id', $age_id)
            ->where('att.agetarj_activa', 1)
            ->select('h.hora_entrada', 'h.hora_salida', 'att.tarj_nro')
            ->first();

        if (!$horario || empty($horario->tarj_nro)) {
            return 0;
        }

        $tarj_nro = $horario->tarj_nro;
        $hora_ent = $horario->hora_entrada;
        $hora_sal = $horario->hora_salida;

        $tiene_horario = !empty($hora_ent);
        $es_24h = ($tiene_horario && $hora_ent == $hora_sal);
        $tolerancia = 9000; // 2.5 hs en segundos? No, helpers.php no definía unidades pero usaba timestamps. 
        // helpers.php: $tolerancia = 9000. abs(timestamp - timestamp) son segundos. 9000s = 2.5h. Correcto.

        // 2. Traer fichadas
        $fecha_inicio = "$anio-$mes-01";
        $fecha_fin = date('Y-m-d', strtotime("$fecha_inicio +1 month +5 days"));

        $fichadas_raw = DB::table('t_fichadas')
            ->where('tarj_nro', $tarj_nro)
            ->where('fich_fecha', '>=', $fecha_inicio)
            ->where('fich_fecha', '<', $fecha_fin)
            ->orderBy('fich_fecha', 'asc')
            ->orderBy('fich_hora', 'asc')
            ->get();

        // Agrupar por día para fallback
        $fichadas_por_dia = [];
        foreach ($fichadas_raw as $f) {
            $d = date('Y-m-d', strtotime($f->fich_fecha));
            $fichadas_por_dia[$d][] = $f;
        }

        // 3. Procesar
        $totalMinutos = 0;
        $entrada_pend_ts = null;
        $ultimo_estado = 'S';

        foreach ($fichadas_raw as $f) {
            $fecha_solo_ymd = date('Y-m-d', strtotime($f->fich_fecha));
            $hora_solo_his = date('H:i:s', strtotime($f->fich_hora));
            $ts_actual = strtotime("$fecha_solo_ymd $hora_solo_his");

            $tipo = 'I';

            if ($tiene_horario) {
                $t_fich = strtotime("1970-01-01 $hora_solo_his UTC");
                $t_ent = strtotime("1970-01-01 $hora_ent UTC");
                $t_sal = strtotime("1970-01-01 $hora_sal UTC");

                if ($es_24h) {
                    if (abs($t_fich - $t_ent) <= $tolerancia) {
                        $tipo = ($ultimo_estado == 'S') ? 'E' : 'S';
                        $ultimo_estado = $tipo;
                    }
                } else {
                    if (abs($t_fich - $t_ent) <= $tolerancia)
                        $tipo = 'E';
                    elseif (abs($t_fich - $t_sal) <= $tolerancia)
                        $tipo = 'S';
                }
            } else {
                // Lógica fallback
                $grupo = $fichadas_por_dia[$fecha_solo_ymd] ?? [];
                if (count($grupo) > 1) {
                    // Comparación de objetos stdClass
                    if ($f->fich_id == $grupo[0]->fich_id)
                        $tipo = 'E'; // Asumiendo fich_id único para comparar identidad
                    elseif ($f->fich_id == $grupo[count($grupo) - 1]->fich_id)
                        $tipo = 'S';
                }
            }

            if ($tipo == 'E') {
                $entrada_pend_ts = $ts_actual;
            } elseif ($tipo == 'S') {
                if ($entrada_pend_ts !== null) {
                    if (date('m', $entrada_pend_ts) == $mes) {
                        $diff = $ts_actual - $entrada_pend_ts;
                        if ($diff > 0 && $diff < 172800) {
                            $totalMinutos += round($diff / 60);
                        }
                    }
                    $entrada_pend_ts = null;
                }
            }
        }

        return $totalMinutos;
    }

    public function convertirMinutosAHoras($minutos_totales)
    {
        if ($minutos_totales < 0)
            $minutos_totales = 0;
        $horas = floor($minutos_totales / 60);
        $minutos = $minutos_totales % 60;
        return sprintf('%02d:%02d', $horas, $minutos);
    }

    /**
     * Obtiene el detalle de fichadas por día para el calendario.
     * Retorna: ['fichadasByDay' => [], 'totalHoras' => '', 'totalMinutos' => int]
     */
    public function getDetalleFichadasMes($age_id, $mes, $anio)
    {
        // 1. Obtener datos de horario y tarjeta
        $horario = DB::table('t_agente as a')
            ->leftJoin('t_horarios_definiciones as h', 'a.horario_definicion_id', '=', 'h.id')
            ->leftJoin('t_age_tarj as att', 'a.age_id', '=', 'att.age_id')
            ->where('a.age_id', $age_id)
            ->where('att.agetarj_activa', 1)
            ->select('h.hora_entrada', 'h.hora_salida', 'att.tarj_nro')
            ->first();

        if (!$horario || empty($horario->tarj_nro)) {
            return ['fichadasByDay' => [], 'totalHoras' => '00:00', 'totalMinutos' => 0];
        }

        $tarj_nro = $horario->tarj_nro;
        $hora_ent = $horario->hora_entrada;
        $hora_sal = $horario->hora_salida;

        $tiene_horario = !empty($hora_ent);
        $es_24h = ($tiene_horario && $hora_ent == $hora_sal);
        $tolerancia = 9000;

        // 2. Traer fichadas
        $fecha_inicio = "$anio-$mes-01";
        $fecha_fin = date('Y-m-d', strtotime("$fecha_inicio +1 month +5 days"));

        $fichadas_raw = DB::table('t_fichadas')
            ->where('tarj_nro', $tarj_nro)
            ->where('fich_fecha', '>=', $fecha_inicio)
            ->where('fich_fecha', '<', $fecha_fin)
            ->orderBy('fich_fecha', 'asc')
            ->orderBy('fich_hora', 'asc')
            ->get();

        // Agrupar raw por día para fallback
        $fichadas_por_dia_raw = [];
        foreach ($fichadas_raw as $f) {
            $d = date('Y-m-d', strtotime($f->fich_fecha));
            $fichadas_por_dia_raw[$d][] = $f;
        }

        // 3. Procesar y Armar Estructura para Calendario
        $totalMinutos = 0;
        $entrada_pend_ts = null;
        $ultimo_estado = 'S';

        $fichadasByDay = []; // Estructura: [dia (int) => [ ['hora'=>'HH:mm', 'tipo'=>'E/S'] ]]

        foreach ($fichadas_raw as $f) {
            $fecha_solo_ymd = date('Y-m-d', strtotime($f->fich_fecha));
            $dia_mes = (int) date('d', strtotime($f->fich_fecha));
            $hora_solo_his = date('H:i:s', strtotime($f->fich_hora));
            $ts_actual = strtotime("$fecha_solo_ymd $hora_solo_his");

            // Solo procesar para el array de resultado las que son del mes solicitado (aunque iteremos un poco mas)
            $es_del_mes = ((int) date('m', strtotime($f->fich_fecha)) == (int) $mes);

            $tipo = 'I';

            if ($tiene_horario) {
                $t_fich = strtotime("1970-01-01 $hora_solo_his UTC");
                $t_ent = strtotime("1970-01-01 $hora_ent UTC");
                $t_sal = strtotime("1970-01-01 $hora_sal UTC");

                if ($es_24h) {
                    if (abs($t_fich - $t_ent) <= $tolerancia) {
                        $tipo = ($ultimo_estado == 'S') ? 'E' : 'S';
                        $ultimo_estado = $tipo;
                    }
                } else {
                    if (abs($t_fich - $t_ent) <= $tolerancia)
                        $tipo = 'E';
                    elseif (abs($t_fich - $t_sal) <= $tolerancia)
                        $tipo = 'S';
                }
            } else {
                $grupo = $fichadas_por_dia_raw[$fecha_solo_ymd] ?? [];
                if (count($grupo) > 1) {
                    if ($f->fich_id == $grupo[0]->fich_id)
                        $tipo = 'E';
                    elseif ($f->fich_id == $grupo[count($grupo) - 1]->fich_id)
                        $tipo = 'S';
                }
            }

            // Calculo de horas (solo importa para el total)
            if ($tipo == 'E') {
                $entrada_pend_ts = $ts_actual;
            } elseif ($tipo == 'S') {
                if ($entrada_pend_ts !== null) {
                    if (date('m', $entrada_pend_ts) == $mes) {
                        $diff = $ts_actual - $entrada_pend_ts;
                        if ($diff > 0 && $diff < 172800) {
                            $totalMinutos += round($diff / 60);
                        }
                    }
                    $entrada_pend_ts = null;
                }
            }

            // Agregar al array de retorno si es del mes
            if ($es_del_mes) {
                $fichadasByDay[$dia_mes][] = [
                    'hora' => date('H:i', strtotime($f->fich_hora)),
                    'tipo' => $tipo
                ];
            }
        }

        return [
            'fichadasByDay' => $fichadasByDay,
            'totalMinutos' => $totalMinutos,
            'totalHoras' => $this->convertirMinutosAHoras($totalMinutos)
        ];
    }

    /**
     * Sincroniza las fichadas desde el sistema externo o hardware.
     */
    /**
     * Sincroniza las fichadas desde el sistema externo (Reloj).
     */
    public function sincronizarFichadas($age_id)
    {
        // 1. Obtener la tarjeta del agente
        $ageTarj = DB::table('t_age_tarj')->where('age_id', $age_id)->where('agetarj_activa', 1)->first();

        if (!$ageTarj) {
            return false; // No tiene tarjeta activa
        }

        $tarj_nro = $ageTarj->tarj_nro;

        // 2. Buscar última fichada en LOCAL (Portal) para optimizar
        $ultimaFichada = DB::table('t_fichadas')
            ->where('tarj_nro', $tarj_nro)
            ->max('fich_fecha');

        $ultimaFecha = $ultimaFichada ?: '2024-01-01';

        // 3. Conectar al RELOJ y traer fichadas NUEVAS
        try {
            // Usamos la conexión secundaria definida en config/database.php
            $nuevas = DB::connection('reloj')->table('t_fichadas')
                ->where('tarj_nro', $tarj_nro)
                ->where('fich_fecha', '>', $ultimaFecha)
                ->orderBy('fich_fecha', 'asc')
                ->orderBy('fich_hora', 'asc')
                ->get();

            if ($nuevas->isEmpty()) {
                return true; // No hay nada nuevo
            }

            // 4. Insertar en LOCAL (Portal) en bloque
            $dataToInsert = [];
            foreach ($nuevas as $f) {
                $dataToInsert[] = [
                    'tarj_nro' => $f->tarj_nro,
                    'fich_fecha' => $f->fich_fecha,
                    'fich_hora' => $f->fich_hora,
                    'fich_cod1' => $f->fich_cod1 ?? null,
                    'fich_cod2' => $f->fich_cod2 ?? null
                ];
            }

            if (!empty($dataToInsert)) {
                // Laravel 8+ soporta insertOrIgnore con arrays
                // Si la tabla no tiene los campos created_at/updated_at, insertOrIgnore es perfecto.
                DB::table('t_fichadas')->insertOrIgnore($dataToInsert);
            }

            return count($dataToInsert);

        } catch (\Exception $e) {
            // Loguear error detallado si la conexión falla o hay error de DB
            \Illuminate\Support\Facades\Log::error("Error crítico sincronizando fichadas (Agente $age_id): " . $e->getMessage(), [
                'exception' => $e
            ]);
            return false;
        }
    }
}




