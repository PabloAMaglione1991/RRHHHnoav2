<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Horario;

class GestionHorarios extends Component
{
    // Variables para Listado
    public $horarios;

    // Variables para Formulario (Crear/Editar)
    public $view = 'list'; // 'list', 'form'
    public $horario_id;
    public $nombre_horario;
    public $hora_entrada;
    public $hora_salida;
    public $horas_totales_dia;
    public $dias_semana = []; // Array de checkbox
    public $activo = true;
    
    public function mount()
    {
        if (!auth()->user()->isRrhh()) {
            abort(403, 'No tienes permiso para gestionar horarios.');
        }
    }

    public function render()
    {
        $this->horarios = Horario::all();
        return view('livewire.gestion-horarios')
            ->extends('layouts.app')
            ->section('content');
    }

    public function create()
    {
        $this->resetInput();
        $this->view = 'form';
    }

    public function edit($id)
    {
        $h = Horario::find($id);
        $this->horario_id = $h->id;
        $this->nombre_horario = $h->nombre_horario;
        $this->hora_entrada = $h->hora_entrada;
        $this->hora_salida = $h->hora_salida;
        $this->horas_totales_dia = $h->horas_totales_dia;
        $this->dias_semana = explode(',', $h->dias_semana);
        $this->activo = $h->activo;

        $this->view = 'form';
    }

    public function store()
    {
        $this->validate([
            'nombre_horario' => 'required',
            'hora_entrada' => 'required',
            'hora_salida' => 'required',
            'dias_semana' => 'required|array|min:1'
        ]);

        $data = [
            'nombre_horario' => $this->nombre_horario,
            'hora_entrada' => $this->hora_entrada,
            'hora_salida' => $this->hora_salida,
            'horas_totales_dia' => $this->horas_totales_dia ?? 0,
            'dias_semana' => implode(',', $this->dias_semana),
            'activo' => $this->activo ? 1 : 0
        ];

        if ($this->horario_id) {
            Horario::find($this->horario_id)->update($data);
        } else {
            Horario::create($data);
        }

        $this->view = 'list';
        session()->flash('success', 'Horario guardado correctamente.');
    }

    public function cancel()
    {
        $this->view = 'list';
    }

    public function delete($id)
    {
        Horario::find($id)->delete();
        session()->flash('success', 'Horario eliminado.');
    }

    private function resetInput()
    {
        $this->horario_id = null;
        $this->nombre_horario = '';
        $this->hora_entrada = '';
        $this->hora_salida = '';
        $this->horas_totales_dia = '';
        $this->dias_semana = [1, 2, 3, 4, 5]; // Default L-V
        $this->activo = true;
    }
}
