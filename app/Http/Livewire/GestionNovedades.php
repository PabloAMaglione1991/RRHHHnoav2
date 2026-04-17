<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Novedad;
use Carbon\Carbon;

class GestionNovedades extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filtros
    public $search = '';
    public $message; // Soporte para $set en el Blade
    public $sortField = 'nov_fecha_publicacion';
    public $sortDirection = 'desc';

    // Propiedades del Modelo (Campos de la Tabla)
    public $novedadId;
    public $titulo;
    public $contenido;
    public $tipo = 'info';
    public $fijada = false;
    public $fecha_publicacion;

    public $isEditMode = false;

    // Reglas de Validación
    protected $rules = [
        'titulo' => 'required|min:5|max:255',
        'contenido' => 'required|min:10',
        'tipo' => 'required|in:info,warning,danger',
        'fijada' => 'boolean',
        'fecha_publicacion' => 'required|date'
    ];

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function mount()
    {
        if (!auth()->user()->isGestorNovedades() && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para gestionar novedades.');
        }
    }

    public function render()
    {
        $novedades = Novedad::where('titulo', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.gestion-novedades', compact('novedades'))
            ->extends('layouts.app')
            ->section('content');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->fecha_publicacion = Carbon::now()->format('Y-m-d\TH:i');
        $this->dispatchBrowserEvent('show-modal');
    }

    public function edit($id)
    {
        $novedad = Novedad::findOrFail($id);

        $this->novedadId = $id;
        $this->titulo = $novedad->titulo;
        $this->contenido = $novedad->contenido;
        $this->tipo = $novedad->tipo ?? 'info';
        $this->fijada = (bool) $novedad->nov_fijada;
        $this->fecha_publicacion = Carbon::parse($novedad->nov_fecha_publicacion)->format('Y-m-d\TH:i');

        $this->isEditMode = true;
        $this->dispatchBrowserEvent('show-modal');
    }

    public function store()
    {
        $this->validate();

        Novedad::updateOrCreate(['id' => $this->novedadId], [
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'tipo' => $this->tipo,
            'nov_fijada' => $this->fijada ? 1 : 0,
            'nov_fecha_publicacion' => $this->fecha_publicacion,
            'nov_activo' => 1
        ]);

        session()->flash('message', $this->novedadId ? 'Novedad actualizada correctamente.' : 'Novedad creada correctamente.');

        $this->dispatchBrowserEvent('hide-modal');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Novedad::find($id)->delete();
        session()->flash('message', 'Novedad eliminada correctamente.');
    }

    public function toggleFijada($id)
    {
        $novedad = Novedad::find($id);
        if ($novedad) {
            $novedad->nov_fijada = !$novedad->nov_fijada;
            $novedad->save();
            session()->flash('message', 'Estado de fijación actualizado.');
        }
    }

    private function resetInputFields()
    {
        $this->novedadId = null;
        $this->titulo = '';
        $this->contenido = '';
        $this->tipo = 'info';
        $this->fijada = false;
        $this->fecha_publicacion = Carbon::now()->format('Y-m-d\TH:i');
    }
}
