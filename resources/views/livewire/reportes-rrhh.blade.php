<div>
    <h2 class="mb-4 text-primary fw-bold">Reportes RRHH</h2>

    <div class="card card-premium shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tipo de Reporte</label>
                    <select wire:model="reporteTipo" class="form-select">
                        <option value="ausentismo">Ausentismo</option>
                        <option value="tardanzas">Tardanzas</option>
                        <option value="licencias">Licencias</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Fecha Inicio</label>
                    <input type="date" wire:model="fechaInicio" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Fecha Fin</label>
                    <input type="date" wire:model="fechaFin" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Departamento</label>
                    <select wire:model="departamentoId" class="form-select">
                        <option value="">Todos</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->tdep_id }}">{{ $dep->tdep_nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Agente</label>
                    <select wire:model="agenteId" class="form-select">
                        <option value="">Todos</option>
                        @foreach($agentes as $ag)
                            <option value="{{ $ag->age_id }}">{{ $ag->age_apell1 }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button wire:click="generarReporte" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-premium shadow-sm border-0 mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Resultados</h5>
        </div>
        <div class="table-responsive">
            @if($reporteTipo == 'ausentismo')
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Agente</th>
                            <th>Departamento</th>
                            <th>Total Ausencias</th>
                            <th>Tipos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resultados as $item)
                            <tr>
                                <td>{{ $item->age_apell1 }}, {{ $item->age_nombre }}</td>
                                <td>{{ $item->tdep_id }}</td>
                                <td><span class="badge bg-danger">{{ $item->total_ausencias }}</span></td>
                                <td><small>{{ $item->tipos_ausencia }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No hay datos para mostrar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($reporteTipo == 'tardanzas')
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Agente</th>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Hora Esperada</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resultados as $item)
                            <tr>
                                <td>{{ $item->age_apell1 }}, {{ $item->age_nombre }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->fich_fecha)->format('d/m/Y') }}</td>
                                <td class="text-danger fw-bold">{{ $item->fich_hora }}</td>
                                <td>{{ $item->hora_entrada }}</td>
                                <td><span class="badge bg-warning">Tardanza</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No hay datos para mostrar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($reporteTipo == 'licencias')
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Agente</th>
                            <th>Tipo</th>
                            <th>Periodo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resultados as $item)
                            <tr>
                                <td>{{ $item->agente->age_apell1 }}, {{ $item->agente->age_nombre }}</td>
                                <td>{{ optional($item->tipoLicencia)->nombre }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y') }} al
                                    {{ \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y') }}
                                </td>
                                <td>
                                    @php
                                        $clase = match ($item->estado) {
                                            'PENDIENTE' => 'bg-warning',
                                            'APROBADA' => 'bg-success',
                                            'RECHAZADA' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $clase }}">{{ $item->estado }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No hay datos para mostrar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>