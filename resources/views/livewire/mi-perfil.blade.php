<div>
    <h2 class="mb-4">Mi Perfil</h2>

    <div class="row">
        <!-- Tarjeta de Información Personal -->
        <div class="col-md-4 mb-4">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden h-100">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($agente->age_nombre, 0, 1) }}{{ substr($agente->age_apell1, 0, 1) }}
                    </div>
                    <h4 class="fw-bold">{{ $agente->age_nombre }} {{ $agente->age_apell1 }}</h4>
                    <p class="text-muted mb-1">{{ $agente->departamento->tdep_nom ?? 'Sin departamento' }}</p>
                    <span class="badge bg-success rounded-pill">Activo</span>

                    <hr class="my-4">

                    <div class="text-start">
                        <p class="mb-2"><i class="bi bi-person-badge me-2 text-primary"></i> <strong>Legajo:</strong>
                            {{ $agente->age_id }}</p>
                        <p class="mb-2"><i class="bi bi-card-heading me-2 text-primary"></i> <strong>Documento:</strong>
                            {{ $agente->age_numdoc }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Seguridad -->
        <div class="col-md-8 mb-4">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="card-header-premium">
                    <i class="bi bi-shield-lock me-2"></i> Seguridad de la Cuenta
                </div>
                <div class="card-body">
                    <h5 class="card-title">Cambiar Contraseña</h5>
                    <p class="text-muted small">Asegúrese de usar una contraseña segura que no utilice en otros sitios.
                    </p>

                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword">
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" wire:model="password">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" wire:model="password_confirmation">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
