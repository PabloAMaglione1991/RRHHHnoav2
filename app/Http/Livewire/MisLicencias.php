<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\LicenciaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MisLicencias extends Component
{
    // Listado
    public $misSolicitudes;

    // Formulario
    public $view = 'list'; // list, form
    public $lt_id;
    public $fecha_inicio;
    public $fecha_fin;
    public $motivo;

    // ID del usuario logueado
    public $age_id;

    public function mount()
    {
        $this->age_id = auth()->user()->age_id;
    }

    protected $rules = [
        'lt_id' => 'required',
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'motivo' => 'required|min:5'
    ];

    public function render(LicenciaService $service)
    {
        $this->misSolicitudes = $service->getMisSolicitudes($this->age_id);
        $tipos_licencia = $service->getTiposLicencia();

        return view('livewire.mis-licencias', [
            'tipos_licencia' => $tipos_licencia
        ])
            ->extends('layouts.app')
            ->section('content');
    }

    public function create()
    {
        $this->resetInput();
        $this->view = 'form';
    }

    public function store(LicenciaService $service)
    {
        $this->validate();

        $service->crearSolicitud([
            'age_id' => $this->age_id,
            'lt_id' => $this->lt_id,
            'fecha_solicitud' => Carbon::now()->format('Y-m-d'),
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'motivo' => $this->motivo,
            'estado' => 'PENDIENTE'
        ]);

        session()->flash('success', 'Solicitud enviada correctamente.');
        $this->view = 'list';
    }

    public function cancel()
    {
        $this->view = 'list';
    }

    private function resetInput()
    {
        $this->lt_id = '';
        $this->fecha_inicio = '';
        $this->fecha_fin = '';
        $this->motivo = '';
    }
}
