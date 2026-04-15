<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Agente;
use App\Models\Fichada;
use App\Models\SolicitudLicencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesRRHH extends Component
{
    public $reporteTipo = 'ausentismo'; // ausentismo, tardanzas, licencias
    public $fechaInicio;
    public $fechaFin;
    public $departamentoId = '';
    public $agenteId = '';

    public function mount()
    {
        // Periodo por defecto: mes actual
        $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function generarReporte()
    {
        // La lógica se manejará en render() para simplificar
    }

    public function render()
    {
        $agentes = Agente::where('age_activo', 1)->get();
        $departamentos = DB::table('t_tdepen')->get();

        $resultados = [];

        if ($this->reporteTipo == 'ausentismo') {
            $resultados = $this->reporteAusentismo();
        } elseif ($this->reporteTipo == 'tardanzas') {
            $resultados = $this->reporteTardanzas();
        } elseif ($this->reporteTipo == 'licencias') {
            $resultados = $this->reporteLicencias();
        }

        return view('livewire.reportes-rrhh', compact('agentes', 'departamentos', 'resultados'))
            ->extends('layouts.app')
            ->section('content');
    }

    private function reporteAusentismo()
    {
        // Contar inasistencias por agente en el periodo
        $query = DB::table('t_inasist')
            ->join('t_age_tarj', 't_inasist.agetarj_id', '=', 't_age_tarj.agetarj_id')
            ->join('t_agente', 't_age_tarj.age_id', '=', 't_agente.age_id')
            ->join('t_artic', 't_inasist.tart_id', '=', 't_artic.tart_id')
            ->whereBetween('t_inasist.inas_fecha', [$this->fechaInicio, $this->fechaFin])
            ->select(
                't_agente.age_id',
                't_agente.age_nombre',
                't_agente.age_apell1',
                't_agente.tdep_id',
                DB::raw('COUNT(*) as total_ausencias'),
                DB::raw('GROUP_CONCAT(DISTINCT t_artic.tart_cod) as tipos_ausencia')
            )
            ->groupBy('t_agente.age_id', 't_agente.age_nombre', 't_agente.age_apell1', 't_agente.tdep_id');

        if ($this->departamentoId) {
            $query->where('t_agente.tdep_id', $this->departamentoId);
        }

        if ($this->agenteId) {
            $query->where('t_agente.age_id', $this->agenteId);
        }

        return $query->get();
    }

    private function reporteTardanzas()
    {
        // Detectar fichadas de entrada después de la hora
        // Asumimos que cada agente tiene un horario definido
        $query = DB::table('t_fichadas')
            ->join('t_age_tarj', 't_fichadas.tarj_nro', '=', 't_age_tarj.tarj_nro')
            ->join('t_agente', 't_age_tarj.age_id', '=', 't_agente.age_id')
            ->join('t_horarios_definiciones', 't_agente.horario_definicion_id', '=', 't_horarios_definiciones.id')
            ->whereBetween('t_fichadas.fich_fecha', [$this->fechaInicio, $this->fechaFin])
            ->select(
                't_agente.age_id',
                't_agente.age_nombre',
                't_agente.age_apell1',
                't_fichadas.fich_fecha',
                't_fichadas.fich_hora',
                't_horarios_definiciones.hora_entrada',
                DB::raw('TIME(t_fichadas.fich_hora) > TIME(t_horarios_definiciones.hora_entrada) as es_tardanza')
            )
            ->having('es_tardanza', '=', 1);

        if ($this->departamentoId) {
            $query->where('t_agente.tdep_id', $this->departamentoId);
        }

        if ($this->agenteId) {
            $query->where('t_agente.age_id', $this->agenteId);
        }

        return $query->get();
    }

    private function reporteLicencias()
    {
        // Resumen de licencias por estado
        $query = SolicitudLicencia::with(['agente', 'tipoLicencia'])
            ->whereBetween('fecha_inicio', [$this->fechaInicio, $this->fechaFin]);

        if ($this->departamentoId) {
            $query->whereHas('agente', function ($q) {
                $q->where('tdep_id', $this->departamentoId);
            });
        }

        if ($this->agenteId) {
            $query->where('age_id', $this->agenteId);
        }

        return $query->get();
    }
}
