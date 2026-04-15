<?php


namespace App\Http\Livewire;


use Livewire\Component;


use App\Models\Agente;


class MiPerfil extends Component
{
    public $agente;
    public $password;
    public $password_confirmation;


    public function mount()
    {
        $this->agente = auth()->user();
    }


    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:4|confirmed',
        ]);


        // 1. Buscar tarjeta activa del agente
        $ageTarj = \App\Models\AgeTarj::where('age_id', $this->agente->age_id)
            ->where('agetarj_activa', 1)
            ->first();


        if (!$ageTarj) {
            $this->addError('password', 'No tienes una tarjeta activa asignada para cambiar la contraseña.');
            return;
        }


        // 2. Buscar o crear registro de contraseña
        $cw = \App\Models\ContraseniaWeb::firstOrNew(['cw_tar_id' => $ageTarj->tarj_nro]);


        // 3. Actualizar contraseña (NOTA: Sistema Legacy usa texto plano)
        $cw->cw_tar_id = $ageTarj->tarj_nro;
        $cw->cw_pass = $this->password;
        // Campos de auditoría si existen, sino ignorar (según modelo)
        $cw->save();


        session()->flash('message', 'Contraseña actualizada correctamente.');
        $this->password = '';
        $this->password_confirmation = '';
    }


    public function render()
    {
        return view('livewire.mi-perfil')
            ->extends('layouts.app')
            ->section('content');
    }
}




