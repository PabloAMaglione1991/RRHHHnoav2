<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\LicenciaService;

class GestionarLicencias extends Component
{
    public $solicitudes;
    public $motivo_rechazo_input = '';
    public $solicitud_rechazo_id = null;

    public function render(LicenciaService $service)
    {
        $this->solicitudes = $service->getSolicitudesPendientes();
        return view('livewire.gestionar-licencias')
            ->extends('layouts.app')
            ->section('content');
    }

    public function aprobar($id, LicenciaService $service)
    {
        try {
            $service->aprobarSolicitud($id);
            session()->flash('success', 'Solicitud aprobada y registrada en el sistema legado.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error al aprobar licencia $id: " . $e->getMessage());
            session()->flash('error', 'Error al sincronizar con el sistema legado. El registro fue logueado para revisión.');
        }
    }

    public function promptRechazar($id)
    {
        $this->solicitud_rechazo_id = $id;
    }

    public function cancelarRechazo()
    {
        $this->solicitud_rechazo_id = null;
        $this->motivo_rechazo_input = '';
    }

    public function confirmarRechazo(LicenciaService $service)
    {
        $this->validate(['motivo_rechazo_input' => 'required|min:5']);
        $service->rechazarSolicitud($this->solicitud_rechazo_id, $this->motivo_rechazo_input);

        session()->flash('success', 'Solicitud rechazada.');
        $this->cancelarRechazo();
    }
}
