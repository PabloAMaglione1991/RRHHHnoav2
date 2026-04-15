<div>
    <h2 class="mb-4">Gestión de Horarios</h2>

    <div class="row">
        <!-- Lista de Horarios -->
        <div class="col-md-5">
            <div class="card card-premium h-100">
                <div class="card-header-premium d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-list-task"></i> Horarios Definidos</h5>
                    <button wire:click="resetInput" class="btn btn-sm btn-primary shadow-sm">
                        <i class="bi bi-plus-lg"></i> Nuevo
                    </button>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($horarios as $hor)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border-0 border-bottom">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-primary">{{ $hor->descripcion }}</div>
                                <small class="text-muted">ID: {{ $hor->id }}</small>
                            </div>
                            <button wire:click="edit({{ $hor->id }})" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0;">
                                <i class="bi bi-pencil-fill" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-calendar-x display-6 mb-2 d-block"></i>
                            Sin horarios definidos.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="col-md-7">
            <div class="card card-premium">
                <div class="card-header-premium bg-white">
                    <h5>
                        @if($horario_id)
                            <i class="bi bi-pencil-square text-warning"></i> Editando Horario
                        @else
                            <i class="bi bi-plus-square text-success"></i> Nuevo Horario
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Descripción General</label>
                        <input type="text" wire:model="descripcion" class="form-control form-control-lg" placeholder="Ej: Lunes a Viernes 08-16hs">
                        @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="table-responsive bg-light rounded p-3 mb-3">
                        <h6 class="text-primary mb-3"><i class="bi bi-clock"></i> Configuración Semanal</h6>
                        <table class="table table-sm table-borderless">
                            <thead>
                                <tr class="text-muted text-uppercase" style="font-size: 0.8rem;">
                                    <th style="width: 20%;">Día</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia)
                                    <tr>
                                        <td class="align-middle fw-bold text-capitalize">{{ $dia }}</td>
                                        <td>
                                            <input type="time" wire:model="{{ $dia }}_entrada" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="time" wire:model="{{ $dia }}_salida" class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        @if($horario_id)
                            <button wire:click="resetInput" class="btn btn-outline-secondary">
                                Cancelar
                            </button>
                        @endif
                        <button wire:click="store" class="btn btn-primary px-4">
                            Save Horario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>