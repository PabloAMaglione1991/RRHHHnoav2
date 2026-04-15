<?php


namespace App\Http\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Agente;
use App\Models\Rol;
use App\Models\Departamento;


class GestionUsuarios extends Component
{
    use WithPagination;


    // Propiedades para Modal y CRUD
    public $isOpen = false;
    public $age_id, $age_nombre, $age_apell1, $age_numdoc, $tdep_id, $jefe_age_id;
    public $selectedRoles = [];
    public $age_activo = true;
    
    public function mount()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para gestionar usuarios.');
        }
    }


    // Filtros
    public $search = '';
    public $roleId = '';
    public $deptoId = '';
    public $jefeId = '';


    // Resetear paginación cuando cambian los filtros
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingRoleId()
    {
        $this->resetPage();
    }
    public function updatingDeptoId()
    {
        $this->resetPage();
    }
    public function updatingJefeId()
    {
        $this->resetPage();
    }


    public function render()
    {
        $query = Agente::query()
            ->with(['roles', 'departamento', 'jefe']);


        // Filtro Nombre/Apellido
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('age_nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('age_apell1', 'like', '%' . $this->search . '%');
            });
        }


        // Filtro Rol
        if (!empty($this->roleId)) {
            $query->whereHas('roles', function ($q) {
                $q->where('t_roles.rol_id', $this->roleId);
            });
        }


        // Filtro Departamento
        if (!empty($this->deptoId)) {
            $query->where('tdep_id', $this->deptoId);
        }


        // Filtro Jefe
        if ($this->jefeId === 'sin_jefe') {
            $query->whereNull('jefe_age_id');
        } elseif (!empty($this->jefeId)) {
            $query->where('jefe_age_id', $this->jefeId);
        }


        $agentes = $query->orderBy('age_apell1')->paginate(10);


        return view('livewire.gestion-usuarios', [
            'agentes' => $agentes,
            'roles' => Rol::orderBy('rol_nombre')->get(),
            'departamentos' => Departamento::orderBy('tdep_nom')->get(),
            'posibles_jefes' => Agente::where('age_activo', 1)->orderBy('age_apell1')->get()
        ])
            ->extends('layouts.app')
            ->section('content');
    }


    public function create()
    {
        $this->resetInput();
        $this->openModal();
    }


    public function openModal()
    {
        $this->isOpen = true;
    }


    public function closeModal()
    {
        $this->isOpen = false;
    }


    private function resetInput()
    {
        $this->age_id = null;
        $this->age_nombre = '';
        $this->age_apell1 = '';
        $this->age_numdoc = '';
        $this->tdep_id = '';
        $this->jefe_age_id = '';
        $this->selectedRoles = [];
        $this->age_activo = true;
    }


    public function store()
    {
        $this->validate([
            'age_nombre' => 'required',
            'age_apell1' => 'required',
            // 'age_id' => 'required|unique:t_agente,age_id,' . $this->age_id . ',age_id', // Validación básica
        ]);


        $agente = Agente::updateOrCreate(['age_id' => $this->age_id], [
            'age_nombre' => $this->age_nombre,
            'age_apell1' => $this->age_apell1,
            'age_numdoc' => $this->age_numdoc,
            'tdep_id' => $this->tdep_id ?: null,
            'jefe_age_id' => $this->jefe_age_id ?: null,
            'age_activo' => $this->age_activo ? 1 : 0
        ]);


        $agente->roles()->sync($this->selectedRoles);


        session()->flash('message', $this->age_id ? 'Agente actualizado.' : 'Agente creado.');
        $this->closeModal();
        $this->resetInput();
    }


    public function edit($id)
    {
        $agente = Agente::findOrFail($id);
        $this->age_id = $id;
        $this->age_nombre = $agente->age_nombre;
        $this->age_apell1 = $agente->age_apell1;
        $this->age_numdoc = $agente->age_numdoc;
        $this->tdep_id = $agente->tdep_id;
        $this->jefe_age_id = $agente->jefe_age_id;
        $this->age_activo = $agente->age_activo;
        $this->selectedRoles = $agente->roles->pluck('rol_id')->toArray();


        $this->openModal();
    }


    public function confirmDelete($id)
    {
        // Simple delete confirmation logic or toast
        $this->delete($id);
    }


    public function delete($id)
    {
        Agente::find($id)->delete();
        session()->flash('message', 'Agente eliminado.');
    }
}




