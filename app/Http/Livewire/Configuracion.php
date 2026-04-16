<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Modulo;

class Configuracion extends Component
{
    public $modulos;
    public $success; // Soporte para $set en el Blade
    public $error;   // Soporte para $set en el Blade

    public function mount()
    {
        $this->refreshModulos();
    }

    public function refreshModulos()
    {
        // Ordenamos por modulo_id ya que no hay columna 'orden'
        $this->modulos = Modulo::orderBy('modulo_id', 'asc')->get();
    }

    public function toggleModulo($id)
    {
        $modulo = Modulo::find($id);

        if ($modulo) {
            // Protección para no apagar el sistema
            if ($modulo->modulo_clave === 'dashboard' || $modulo->modulo_clave === 'configuracion') {
                session()->flash('error', 'No se puede desactivar un módulo crítico.');
                return;
            }

            $modulo->modulo_activo = !$modulo->modulo_activo;
            $modulo->save();

            $this->refreshModulos();
            session()->flash('success', 'Estado del módulo "' . $modulo->modulo_nombre . '" actualizado.');
        }
    }

    public function render()
    {
        return view('livewire.configuracion')
            ->extends('layouts.app')
            ->section('content');
    }
}
