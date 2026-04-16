<div>
    <h2 class="mb-4">Mis Licencias</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="card-header-premium">
                    <h5><i class="bi bi-plus-circle"></i> Nueva Solicitud</h5>
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

                    <form wire:submit.prevent="store">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Licencia</label>
                            {{-- IMPORTANTE: Cambié lt_id por licencia_tipo_id para que coincida con tu base de datos --}}
                            <select wire:model="licencia_tipo_id" class="form-select">
                                <option value="">-- Seleccionar --</option>
                                @foreach($tipos_licencia as $tipo)
                                    {{-- CORRECCIÓN: Usamos $tipo->id porque lt_id no existe en esa tabla --}}
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('licencia_tipo_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" wire:model="fecha_inicio" class="form-control">
                            @error('fecha_inicio') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" wire:model="fecha_fin" class="form-control">
                            @error('fecha_fin') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motivo (Opcional)</label>
                            <textarea wire:model="motivo" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Enviar Solicitud
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="card-header-premium">
                    <h5><i class="bi bi-clock-history"></i> Historial de Solicitudes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-premium mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha Solicitud</th>
                                    <th>Tipo</th>
                                    <th>Período</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($misSolicitudes as $sol)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($sol->fecha_solicitud)->format('d/m/Y') }}</td>
                                        <td>{{ optional($sol->tipoLicencia)->nombre }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($sol->fecha_inicio)->format('d/m/Y') }} al
                                            {{ \Carbon\Carbon::parse($sol->fecha_fin)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            @php
                                                $clase = match ($sol->estado) {
                                                    'PENDIENTE' => 'bg-warning',
                                                    'APROBADA' => 'bg-success',
                                                    'RECHAZADA' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge badge-pill {{ $clase }}">{{ $sol->estado }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            No tienes solicitudes registradas.
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
