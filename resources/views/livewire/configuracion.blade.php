<div>
    <h2 class="mb-4">Configuración del Sistema</h2>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 py-1"><i class="bi bi-toggles"></i> Gestión de Módulos (Sidebar)</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">Active o desactive los módulos que estarán visibles en la barra lateral para todos los usuarios.</p>
            
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Módulo</th>
                            <th>Ruta (Clave)</th>
                            <th>Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modulos as $modulo)
                            <tr>
                                <td>{{ $modulo->modulo_id }}</td>
                                <td class="fw-bold">{{ $modulo->modulo_nombre }}</td>
                                <td><code>{{ $modulo->modulo_clave }}</code></td>
                                <td>
                                    @if($modulo->modulo_activo)
                                        <span class="badge bg-success rounded-pill">Activo</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" 
                                               wire:click="toggleModulo({{ $modulo->modulo_id }})"
                                               {{ $modulo->modulo_activo ? 'checked' : '' }}
                                               {{ ($modulo->modulo_clave == 'dashboard' || $modulo->modulo_clave == 'configuracion') ? 'disabled' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
