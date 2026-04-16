<div>
    @if($isAdmin)
        <!-- Admin Actions Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-4 border-warning">
            <div class="card-header-premium bg-warning text-dark">
                <i class="bi bi-shield-lock-fill me-2"></i> Acciones de Administrador
            </div>
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title fw-bold">Sincronización de Fichadas</h5>
                    <p class="card-text text-muted mb-0 small">Importar manualmente los últimos registros del reloj de
                        fichadas.</p>
                </div>

                <button wire:click="sincronizar" class="btn btn-dark" wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        <i class="bi bi-arrow-repeat me-1"></i> Sincronizar
                    </span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-1"></span> Sincronizando...
                    </span>
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endif
</div>
