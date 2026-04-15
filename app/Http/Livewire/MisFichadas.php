<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\FichadaService;
use Carbon\Carbon;

class MisFichadas extends Component
{
    public $mes;
    public $anio;

    protected $queryString = ['mes', 'anio'];

    public function mount()
    {
        $this->mes = request()->query('mes', date('n'));
        $this->anio = request()->query('anio', date('Y'));
    }

    public function hoy()
    {
        $this->mes = date('n');
        $this->anio = date('Y');
    }

    public function sincronizar(FichadaService $fichadaService)
    {
        $age_id = auth()->user()->age_id;
        $resultado = $fichadaService->sincronizarFichadas($age_id);

        if ($resultado !== false) {
            if (is_numeric($resultado) && $resultado > 0) {
                session()->flash('message', "Sincronización exitosa. Se importaron $resultado fichadas nuevas.");
            } else {
                session()->flash('message', "El sistema está al día. No hubo fichadas nuevas.");
            }
        } else {
            session()->flash('message', 'Error al conectar con el Reloj o tarjeta no asignada.');
        }

        // Recargar vista o mantener estado
    }

    public function render(FichadaService $fichadaService)
    {
        $age_id = auth()->user()->age_id;

        $data = $fichadaService->getDetalleFichadasMes($age_id, $this->mes, $this->anio);

        $calendario = $this->buildCalendar($this->mes, $this->anio, $data['fichadasByDay']);

        return view('livewire.mis-fichadas', [
            'calendario' => $calendario,
            'totalHorasMensuales' => $data['totalHoras'],
            'mes' => $this->mes,
            'anio' => $this->anio
        ])
            ->extends('layouts.app')
            ->section('content');
    }

    private function buildCalendar($mes, $anio, $fichadasByDay)
    {
        $date = Carbon::createFromDate($anio, $mes, 1);
        $daysInMonth = $date->daysInMonth;

        // Start date of the calendar (start on Monday)
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY);
        // End date of the calendar (end on Sunday)
        $endOfWeek = $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $calendario = [];
        $semana = [];

        $currentDay = $startOfWeek->copy();

        while ($currentDay->lte($endOfWeek)) {
            $dayNum = $currentDay->day;
            $isMonthActual = $currentDay->month == $mes;

            $fichadas = $isMonthActual && isset($fichadasByDay[$dayNum]) ? $fichadasByDay[$dayNum] : [];

            // Calculo simple de horas trabajadas en el día (si hay fichadas E y S pares)
            $horasTrabajadas = 0; // Se podría calcular mejor desde el servicio, pero for now 0

            $diaInfo = [
                'fecha' => $currentDay->format('Y-m-d'),
                'dia' => $dayNum,
                'es_mes_actual' => $isMonthActual,
                'fichadas' => $fichadas,
                'horas_trabajadas' => $horasTrabajadas,
                'inasistencia' => false, // Todavía no calculado
                'licencia' => null,
                'feriado' => null
            ];

            $semana[] = $diaInfo;

            if (count($semana) == 7) {
                $calendario[] = $semana;
                $semana = [];
            }

            $currentDay->addDay();
        }

        return $calendario;
    }
}


