<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Agente;
use App\Services\FichadaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FichadasJefe extends Component
{
    public $mesActual;
    public $anioActual;
    public $agente_id; 
    public $search = '';

    protected $queryString = [
        'mesActual' => ['except' => ''],
        'anioActual' => ['except' => ''],
        'agente_id' => ['except' => '']
    ];

    public function mount()
    {
        $this->mesActual = request()->query('mesActual', date('n'));
        $this->anioActual = request()->query('anioActual', date('Y'));
    }

    public function seleccionarAgente($id)
    {
        $this->agente_id = $id;
    }

    public function render(FichadaService $fichadaService)
    {
        $user = Auth::user();

        // 1. Obtener subordinados con filtro de búsqueda
        $agentes = Agente::where('jefe_age_id', $user->age_id)
            ->where('age_activo', 1)
            ->where(function ($q) {
                if (!empty($this->search)) {
                    $searchTerm = '%' . trim($this->search) . '%';
                    $q->where('age_nombre', 'like', $searchTerm)
                      ->orWhere('age_apell1', 'like', $searchTerm)
                      ->orWhere('age_numdoc', 'like', $searchTerm);
                }
            })
            ->orderBy('age_apell1')
            ->get();

        $datosAgente = null;
        $calendario = [];
        $totalHoras = '00:00';

        // 2. Si hay un agente seleccionado, procesar su calendario
        if ($this->agente_id) {
            $esAdmin = $user->hasRole(['ADMIN', 'RRHH']);
            $esSuAgente = Agente::where('age_id', $this->agente_id)
                                ->where('jefe_age_id', $user->age_id)
                                ->exists();

            if ($esAdmin || $esSuAgente) {
                $fichadaData = $fichadaService->getDetalleFichadasMes($this->agente_id, $this->mesActual, $this->anioActual);
                
                $fichadasFormateadas = [];
                if (isset($fichadaData['fichadasByDay'])) {
                    foreach ($fichadaData['fichadasByDay'] as $dia => $fichadas) {
                        foreach ($fichadas as $f) {
                            // Convertimos a array para asegurar compatibilidad total
                            $fArray = (array) $f;

                            // Buscamos la hora en diferentes posibles llaves
                            $horaRaw = $fArray['fichada_hora'] ?? $fArray['hora'] ?? null;
                            // Buscamos el tipo (Entrada/Salida)
                            $tipoRaw = $fArray['fichada_tipo'] ?? $fArray['tipo'] ?? 'S';

                            $fichadasFormateadas[$dia][] = [
                                'hora' => $horaRaw ? substr($horaRaw, 0, 5) : '--:--',
                                'tipo' => (str_contains(strtolower($tipoRaw), 'ent') || $tipoRaw == 'E') ? 'E' : 'S'
                            ];
                        }
                    }
                }

                $calendario = $this->buildCalendar($this->mesActual, $this->anioActual, $fichadasFormateadas);
                $totalHoras = $fichadaData['totalHoras'] ?? '00:00';
                $datosAgente = Agente::find($this->agente_id);
            }
        }

        return view('livewire.fichadas-jefe', [
            'agentes' => $agentes,
            'calendario' => $calendario,
            'totalHoras' => $totalHoras,
            'datosAgente' => $datosAgente
        ])
        ->extends('layouts.app')
        ->section('content');
    }

    private function buildCalendar($mes, $anio, $fichadasByDay)
    {
        $date = Carbon::createFromDate($anio, $mes, 1);
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $calendario = [];
        $semana = [];
        $currentDay = $startOfWeek->copy();

        while ($currentDay->lte($endOfWeek)) {
            $dayNum = $currentDay->day;
            $isMonthActual = $currentDay->month == $mes;
            $fichadas = $isMonthActual && isset($fichadasByDay[$dayNum]) ? $fichadasByDay[$dayNum] : [];

            $semana[] = [
                'fecha' => $currentDay->format('Y-m-d'),
                'dia' => $dayNum,
                'es_mes_actual' => $isMonthActual,
                'fichadas' => $fichadas
            ];

            if (count($semana) == 7) {
                $calendario[] = $semana;
                $semana = [];
            }
            $currentDay->addDay();
        }
        return $calendario;
    }
}
