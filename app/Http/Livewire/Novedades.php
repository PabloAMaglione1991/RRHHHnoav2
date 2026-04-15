<?php


namespace App\Http\Livewire;


use Livewire\Component;


class Novedades extends Component
{
    public $selectedNovedad = null;


    public function render()
    {
        // Ordenar primero por fijadas, luego por fecha de publicación. Limitar a 3.
        $novedades = \App\Models\Novedad::where('nov_activo', 1)
            ->orderBy('nov_fijada', 'desc')
            ->orderBy('nov_fecha_publicacion', 'desc')
            ->take(3)
            ->get();
        return view('livewire.novedades', compact('novedades'));
    }


    public function selectNovedad($id)
    {
        $this->selectedNovedad = \App\Models\Novedad::find($id);
        $this->dispatchBrowserEvent('open-novedad-modal');
    }
}




