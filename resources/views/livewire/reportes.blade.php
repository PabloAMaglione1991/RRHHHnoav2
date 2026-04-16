div>
    <h2 class="mb-4">Informes y Reportes</h2>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Agente</label>
                    <select wire:model="agente_id" class="form-select">
                        <option value="">-- Todos --</option>
                        @foreach($agentes as $age)
                            <option value="{{ $age->age_id }}">{{ $age->age_apell1 }}, {{ $age->age_nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tipo Licencia</label>
                    <select wire:model="lt_id" class="form-select">
                        <option value="">-- Todos --</option>
                        @foreach($tipos_licencia as $tipo)
                            <option value="{{ $tipo->lt_id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Estado</label>
                    <select wire:model="estado" class="form-select">
                        <option value="">-- Todos --</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Desde</label>
                    <input type="date" wire:model="fecha_desde" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Hasta</label>
                    <input type="date" wire:model="fecha_hasta" class="form-control">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button wire:click="limpiarFiltros" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                @if($resultados->count() > 0)
                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                        <strong>Mostrando {{ $resultados->total() }} resultados.</strong>
                    </div>
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Agente</th>
                                <th>Tipo</th>
                                <th>Fechas</th>
                                <th>Estado</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resultados as $sol)
                                <tr>
                                    <td>#{{ $sol->solicitud_id }}</td>
                                    <td>
                                        <strong>{{ optional($sol->agente)->age_apell1 }}, {{ optional($sol->agente)->age_nombre }}</strong>
                                    </td>
                                    <td>{{ optional($sol->tipoLicencia)->nombre }}</td>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($sol->fecha_inicio)->format('d/m/Y') }} al 
                                        {{ \Carbon\Carbon::parse($sol->fecha_fin)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @php
                                            $clase = match(strtolower($sol->estado)) {
                                                'pendiente' => 'bg-warning text-dark',
                                                'aprobada'  => 'bg-success',
                                                'rechazada' => 'bg-danger',
                                                default     => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge rounded-pill {{ $clase }}">{{ strtoupper($sol->estado) }}</span>
                                    </td>
                                    <td class="small text-muted">{{ Str::limit($sol->motivo, 40) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center p-5">
                        <i class="bi bi-search display-6 text-muted mb-3 d-block"></i>
                        <p class="text-muted">No se encontraron registros con los filtros seleccionados.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $resultados->links() }}
    </div>
</div>
