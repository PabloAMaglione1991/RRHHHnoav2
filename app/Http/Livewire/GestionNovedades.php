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


    // Listeners para eventos del navegador (opcional, pero útil)
    protected $listeners = ['deleteConfirmed' => 'delete'];


    // Método Mount explícito para evitar problemas de inyección
    public function mount()
    {
        if (!auth()->user()->isGestorNovedades() && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para gestionar novedades.');
        }
    }


    public function render()
    {
        // Usamos los nombres de columna correctos (nov_...)
        $novedades = Novedad::where('nov_titulo', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);


        return view('livewire.gestion-novedades', compact('novedades'))
            ->extends('layouts.app')
            ->section('content');
    }


    // Abrir Modal de Creación
    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->fecha_publicacion = Carbon::now()->format('Y-m-d\TH:i');
        // Emitimos evento para que JS abra el modal (si usas script)
        $this->dispatchBrowserEvent('show-modal');
    }


    // Abrir Modal de Edición
    public function edit($id)
    {
        // NOTA: Aquí $id viene del click en el botón, no por inyección.
        $novedad = Novedad::findOrFail($id);


        $this->novedadId = $id;
        $this->titulo = $novedad->nov_titulo;
        $this->contenido = $novedad->nov_contenido_largo; // Asumiendo que es contenido largo
        $this->tipo = $novedad->nov_tipo ?? 'info';
        $this->fijada = (bool) $novedad->nov_fijada;
        $this->fecha_publicacion = Carbon::parse($novedad->nov_fecha_publicacion)->format('Y-m-d\TH:i');


        $this->isEditMode = true;


        $this->dispatchBrowserEvent('show-modal');
    }


    // Guardar (Crear o Actualizar)
    public function store()
    {
        $this->validate();


        Novedad::updateOrCreate(['nov_id' => $this->novedadId], [
            'nov_titulo' => $this->titulo,
            'nov_contenido_largo' => $this->contenido,
            'nov_contenido_corto' => substr($this->contenido, 0, 100) . '...', // Generar resumen auto
            'nov_tipo' => $this->tipo,
            'nov_fijada' => $this->fijada ? 1 : 0,
            'nov_fecha_publicacion' => $this->fecha_publicacion,
            'nov_activo' => 1
        ]);


        session()->flash('message', $this->novedadId ? 'Novedad actualizada correctamente.' : 'Novedad creada correctamente.');


        $this->dispatchBrowserEvent('hide-modal');
        $this->resetInputFields();
    }


    // Eliminar
    public function delete($id)
    {
        Novedad::find($id)->delete();
        session()->flash('message', 'Novedad eliminada correctamente.');
    }


    // Alternar Fijado
    public function toggleFijada($id)
    {
        $novedad = Novedad::find($id);
        if ($novedad) {
            $novedad->nov_fijada = !$novedad->nov_fijada;
            $novedad->save();
            session()->flash('message', 'Estado de fijación actualizado.');
        }
    }


    // Resetear Campos
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




