<div>
    <h2 class="mb-4">Gestionar Licencias (RRHH)</h2>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="card-header-premium">
            <h5><i class="bi bi-hourglass-split"></i> Solicitudes Pendientes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-premium mb-0">
                    <thead>
                        <tr>
                            <th>Fecha Solicitud</th>
                            <th>Agente</th>
                            <th>Tipo</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Motivo</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $sol)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($sol->fecha_solicitud)->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ optional($sol->agente)->age_apell1 }},
                                        {{ optional($sol->agente)->age_nombre }}</strong>
                                </td>
                                <td>{{ optional($sol->tipoLicencia)->nombre }}</td>
                                <td>{{ \Carbon\Carbon::parse($sol->fecha_inicio)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($sol->fecha_fin)->format('d/m/Y') }}</td>
                                <td><small class="text-muted">{{ Str::limit($sol->motivo, 50) }}</small></td>
                                <td class="text-end">
                                    <button wire:click="aprobarSolicitud({{ $sol->solicitud_id }})"
                                        class="btn btn-sm btn-success text-white" title="Aprobar">
                                        <i class="bi bi-check-lg"></i> Aprobar
                                    </button>
                                    <button wire:click="rechazarSolicitud({{ $sol->solicitud_id }})"
                                        class="btn btn-sm btn-danger text-white" title="Rechazar">
                                        <i class="bi bi-x-lg"></i> Rechazar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-check2-circle display-4 d-block mb-3 text-secondary"></i>
                                    <h5>No hay solicitudes pendientes</h5>
                                    <p>¡Buen trabajo! Todo está al día.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
