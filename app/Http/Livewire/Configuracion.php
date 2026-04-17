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
        // Ordenamos por la columna 'orden' definida en el esquema
        $this->modulos = Modulo::orderBy('orden', 'asc')->get();
    }

    public function toggleModulo($id)
    {
        $modulo = Modulo::find($id);

        if ($modulo) {
            // Protección para no apagar el sistema (usando columna 'route')
            if ($modulo->route === 'dashboard' || $modulo->route === 'configuracion') {
                session()->flash('error', 'No se puede desactivar un módulo crítico.');
                return;
            }

            $modulo->activo = !$modulo->activo;
            $modulo->save();

            $this->refreshModulos();
            session()->flash('success', 'Estado del módulo "' . $modulo->nombre . '" actualizado.');
        }
    }

    public function render()
    {
        return view('livewire.configuracion')
            ->extends('layouts.app')
            ->section('content');
    }
}
