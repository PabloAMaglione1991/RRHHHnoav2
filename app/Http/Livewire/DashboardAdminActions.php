<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\FichadaService;

class DashboardAdminActions extends Component
{
    public function mount()
    {
        // Verificar rol
        $this->isAdmin = auth()->user()->roles->contains('rol_nombre', 'admin');
    }

    public function sincronizar(FichadaService $service)
    {
        if (!$this->isAdmin) {
            return;
        }

        $age_id = auth()->user()->age_id;

        $service->sincronizarFichadas($age_id);

        session()->flash('message', 'Fichadas sincronizadas correctamente con el reloj.');
    }

    public function render()
    {
        return view('livewire.dashboard-admin-actions');
    }
}
