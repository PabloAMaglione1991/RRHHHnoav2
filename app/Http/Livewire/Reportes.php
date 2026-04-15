<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SolicitudLicencia;
use App\Models\Agente;
use App\Models\LicenciaTipo;

class Reportes extends Component
{
    use WithPagination;

    public $agente_id = '';
    public $lt_id = '';
    public $estado = '';
    public $fecha_desde = '';
    public $fecha_hasta = '';

    protected $queryString = [
        'agente_id' => ['except' => ''],
        'lt_id' => ['except' => ''],
        'estado' => ['except' => ''],
        'fecha_desde' => ['except' => ''],
        'fecha_hasta' => ['except' => '']
    ];

    public function updating()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SolicitudLicencia::with(['agente', 'tipoLicencia']);

        if ($this->agente_id) {
            $query->where('age_id_agente', $this->agente_id);
        }

        if ($this->lt_id) {
            $query->where('licencia_tipo_id', $this->lt_id);
        }

        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        if ($this->fecha_desde) {
            $query->where('fecha_inicio', '>=', $this->fecha_desde);
        }

        if ($this->fecha_hasta) {
            $query->where('fecha_inicio', '<=', $this->fecha_hasta);
        }

        $resultados = $query->orderBy('fecha_solicitud', 'desc')->paginate(20);

        return view('livewire.reportes', [
            'resultados' => $resultados,
            'agentes' => Agente::orderBy('age_apell1')->get(),
            'tipos_licencia' => LicenciaTipo::all()
        ])
        ->extends('layouts.app')
        ->section('content');
    }

    public function limpiarFiltros()
    {
        $this->reset(['agente_id', 'lt_id', 'estado', 'fecha_desde', 'fecha_hasta']);
    }
}
