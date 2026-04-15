<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TipoLicencia;
use Livewire\WithPagination;

class GestionTiposLicencias extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'nombre';
    public $sortDirection = 'asc';

    // Campos formulario
    public $lt_id;
    public $nombre;
    public $codigo_legacy;
    public $regla_calculo_facturacion;
    public $grupo_descuento;

    public $isEditMode = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'codigo_legacy' => 'required|max:20',
        'regla_calculo_facturacion' => 'required',
        'grupo_descuento' => 'nullable'
    ];

    public function render()
    {
        $tipos = TipoLicencia::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.gestion-tipos-licencias', compact('tipos'))
            ->extends('layouts.app')
            ->section('content');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->dispatchBrowserEvent('show-modal');
    }

    public function edit($id)
    {
        $tipo = TipoLicencia::findOrFail($id);
        $this->lt_id = $id;
        $this->nombre = $tipo->nombre;
        $this->codigo_legacy = $tipo->codigo_legacy;
        $this->regla_calculo_facturacion = $tipo->regla_calculo_facturacion;
        $this->grupo_descuento = $tipo->grupo_descuento;

        $this->isEditMode = true;
        $this->dispatchBrowserEvent('show-modal');
    }

    public function store()
    {
        $this->validate();

        TipoLicencia::updateOrCreate(['lt_id' => $this->lt_id], [
            'nombre' => $this->nombre,
            'codigo_legacy' => $this->codigo_legacy,
            'regla_calculo_facturacion' => $this->regla_calculo_facturacion,
            'grupo_descuento' => $this->grupo_descuento
        ]);

        session()->flash('message', $this->lt_id ? 'Tipo de licencia actualizado.' : 'Tipo de licencia creado.');
        $this->dispatchBrowserEvent('hide-modal');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        // Verificar dependencias antes de borrar (opcional, por ahora directo)
        TipoLicencia::find($id)->delete();
        session()->flash('message', 'Tipo de licencia eliminado.');
    }

    private function resetInputFields()
    {
        $this->lt_id = null;
        $this->nombre = '';
        $this->codigo_legacy = '';
        $this->regla_calculo_facturacion = 'total'; // default
        $this->grupo_descuento = '';
    }
}
