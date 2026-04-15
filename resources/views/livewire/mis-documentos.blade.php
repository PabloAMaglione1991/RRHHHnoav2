<div>
    <h2 class="mb-4 text-primary fw-bold">Mis Documentos</h2>

    <div class="row">
        <!-- Formulario de Subida -->
        <div class="col-md-4">
            <div class="card card-premium">
                <div class="card-header-premium">
                    <h5><i class="bi bi-cloud-upload"></i> Subir Documento</h5>
                </div>
                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="subirDocumento">
                        @if($esAdmin)
                            <div class="mb-3">
                                <label class="form-label">Agente</label>
                                <select wire:model="agenteSeleccionado" class="form-select">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($agentes as $ag)
                                        <option value="{{ $ag->age_id }}">{{ $ag->age_apell1 }}, {{ $ag->age_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Tipo de Documento</label>
                            <select wire:model="tipo_documento" class="form-select">
                                <option value="">-- Seleccionar --</option>
                                <option value="DNI">DNI</option>
                                <option value="Certificado Médico">Certificado Médico</option>
                                <option value="Título">Título</option>
                                <option value="Certificado Vacunación">Certificado Vacunación</option>
                                <option value="Otro">Otro</option>
                            </select>
                            @error('tipo_documento') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Archivo</label>
                            <input type="file" wire:model="archivo" class="form-control">
                            @error('archivo') <span class="text-danger small">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="archivo" class="text-primary small mt-2">
                                <i class="bi bi-arrow-repeat"></i> Cargando archivo...
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones (Opcional)</label>
                            <textarea wire:model="observaciones" class="form-control" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled"
                            wire:target="archivo">
                            <i class="bi bi-upload"></i> Subir Documento
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de Documentos -->
        <div class="col-md-8">
            <div class="card card-premium">
                <div class="card-header-premium">
                    <h5><i class="bi bi-files"></i> Documentos Guardados</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-premium mb-0">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Nombre Archivo</th>
                                    <th>Fecha Subida</th>
                                    <th>Subido Por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documentos as $doc)
                                    <tr>
                                        <td><span class="badge bg-info">{{ $doc->tipo_documento }}</span></td>
                                        <td>{{ $doc->nombre_archivo }}</td>
                                        <td>{{ \Carbon\Carbon::parse($doc->fecha_subida)->format('d/m/Y H:i') }}</td>
                                        <td>{{ optional($doc->subidoPor)->nombre_completo }}</td>
                                        <td>
                                            <button wire:click="descargarDocumento({{ $doc->doc_id }})"
                                                class="btn btn-sm btn-outline-primary" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </button>
                                            @if($esAdmin)
                                                <button wire:click="eliminarDocumento({{ $doc->doc_id }})"
                                                    class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                    onclick="return confirm('¿Seguro que deseas eliminar este documento?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            No hay documentos guardados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>