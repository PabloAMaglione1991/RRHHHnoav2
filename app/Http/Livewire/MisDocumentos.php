<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DocumentoAgente;
use App\Models\Agente;
use Illuminate\Support\Facades\Storage;

class MisDocumentos extends Component
{
    use WithFileUploads;

    public $archivo;
    public $tipo_documento;
    public $observaciones;

    public $esAdmin = false;
    public $agenteSeleccionado;

    protected $rules = [
        'archivo' => 'required|file|max:10240', // 10MB max
        'tipo_documento' => 'required|string|max:50',
        'observaciones' => 'nullable|string'
    ];

    public function mount()
    {
        $this->esAdmin = auth()->user()->roles->pluck('rol_nombre')->contains('admin') ||
            auth()->user()->roles->pluck('rol_nombre')->contains('rrhh');

        if ($this->esAdmin) {
            $this->agenteSeleccionado = auth()->id();
        }
    }

    public function subirDocumento()
    {
        $this->validate();

        $agenteId = $this->esAdmin && $this->agenteSeleccionado ?
            $this->agenteSeleccionado : auth()->id();

        // Guardar archivo en storage
        $nombreArchivo = time() . '_' . $this->archivo->getClientOriginalName();
        $ruta = $this->archivo->storeAs('documentos', $nombreArchivo);

        // Crear registro en BD
        DocumentoAgente::create([
            'age_id' => $agenteId,
            'tipo_documento' => $this->tipo_documento,
            'nombre_archivo' => $this->archivo->getClientOriginalName(),
            'ruta_archivo' => $ruta,
            'subido_por' => auth()->id(),
            'observaciones' => $this->observaciones
        ]);

        session()->flash('message', 'Documento subido exitosamente.');
        $this->reset(['archivo', 'tipo_documento', 'observaciones']);
    }

    public function descargarDocumento($docId)
    {
        $documento = DocumentoAgente::findOrFail($docId);

        // Verificar permisos
        if (!$this->esAdmin && $documento->age_id != auth()->id()) {
            session()->flash('error', 'No tienes permiso para descargar este documento.');
            return;
        }

        return Storage::download($documento->ruta_archivo, $documento->nombre_archivo);
    }

    public function eliminarDocumento($docId)
    {
        $documento = DocumentoAgente::findOrFail($docId);

        // Solo admin/rrhh puede eliminar
        if (!$this->esAdmin) {
            session()->flash('error', 'No tienes permiso para eliminar documentos.');
            return;
        }

        // Eliminar archivo físico
        if (Storage::exists($documento->ruta_archivo)) {
            Storage::delete($documento->ruta_archivo);
        }

        $documento->delete();
        session()->flash('message', 'Documento eliminado.');
    }

    public function render()
    {
        $agenteId = $this->esAdmin && $this->agenteSeleccionado ?
            $this->agenteSeleccionado : auth()->id();

        $documentos = DocumentoAgente::where('age_id', $agenteId)
            ->with('subidoPor')
            ->orderBy('fecha_subida', 'desc')
            ->get();

        $agentes = $this->esAdmin ? Agente::where('age_activo', 1)->get() : collect();

        return view('livewire.mis-documentos', compact('documentos', 'agentes'))
            ->extends('layouts.app')
            ->section('content');
    }
}
