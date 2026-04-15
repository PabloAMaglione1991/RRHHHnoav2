<div>
    <h2 class="mb-4">Gestión de Usuarios</h2>

    {{-- Filtros --}}
    <div class="card card-premium mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Buscar por Nombre/Apellido..."
                        wire:model="search">
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="roleId">
                        <option value="">-- Todos los Roles --</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->rol_id }}">{{ $rol->rol_nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="deptoId">
                        <option value="">-- Todos los Deptos --</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->tdep_id }}">{{ $dep->tdep_nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button wire:click="create" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Nuevo Agente
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card card-premium">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-premium mb-0">
                    <thead>
                        <tr>
                            <th>Agente</th>
                            <th>Legajo</th>
                            <th>Estado</th>
                            <th>Depto</th>
                            <th>Roles</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agentes as $agente)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            {{ substr($agente->age_nombre, 0, 1) }}{{ substr($agente->age_apell1, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $agente->age_apell1 }},
                                                {{ $agente->age_nombre }}</div>
                                            <small class="text-muted">{{ $agente->age_numdoc }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $agente->age_id }}</td>
                                <td>
                                    @if($agente->age_activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $agente->tdep_id ? 'bg-info text-dark' : 'bg-secondary' }}">
                                        {{ $agente->departamento->tdep_nom ?? 'Sin Depto' }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($agente->roles as $rol)
                                        <span class="badge bg-primary rounded-pill">{{ $rol->rol_nombre }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <button wire:click="edit({{ $agente->age_id }})" class="btn btn-sm btn-outline-primary"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $agente->age_id }})"
                                        class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $agentes->links() }}
    </div>

    <!-- Modal -->
    @if($isOpen)
        <div class="modal show d-block" style="background: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">{{ $age_id ? 'Editar Agente' : 'Nuevo Agente' }}</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" wire:model="age_nombre">
                                    @error('age_nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" class="form-control" wire:model="age_apell1">
                                    @error('age_apell1') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ID (Legajo)</label>
                                    <input type="number" class="form-control" wire:model="age_id" {{ $age_id ? 'readonly' : '' }}>
                                    @error('age_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Documento</label>
                                    <input type="text" class="form-control" wire:model="age_numdoc">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Departamento</label>
                                    <select class="form-select" wire:model="tdep_id">
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($departamentos as $dep)
                                            <option value="{{ $dep->tdep_id }}">{{ $dep->tdep_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Roles</label>
                                    <select class="form-select" multiple wire:model="selectedRoles">
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->rol_id }}">{{ $rol->rol_nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Ctrl + Click para múltiples</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jefe</label>
                                    <select class="form-select" wire:model="jefe_age_id">
                                        <option value="">-- Sin Jefe --</option>
                                        @foreach($posibles_jefes as $jefe)
                                            <option value="{{ $jefe->age_id }}">{{ $jefe->age_apell1 }}, {{ $jefe->age_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click="store">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>