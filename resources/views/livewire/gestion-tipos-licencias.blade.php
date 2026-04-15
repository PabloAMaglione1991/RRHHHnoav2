<div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="text-primary fw-bold mb-0">Tipos de Licencias</h2>
            <p class="text-muted small">Gestión de códigos y reglas de cálculo</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button wire:click="create" class="btn btn-premium shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Tipo
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-premium shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-search text-muted"></i></span>
                        <input wire:model="search" type="text" class="form-control border-start-0 ps-0"
                            placeholder="Buscar por nombre...">
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Código Legacy</th>
                        <th>Nombre</th>
                        <th>Regla Cálculo</th>
                        <th>Grupo Descuento</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tipos as $tipo)
                        <tr>
                            <td class="ps-4"><span class="badge bg-secondary">{{ $tipo->codigo_legacy }}</span></td>
                            <td class="fw-bold text-dark">{{ $tipo->nombre }}</td>
                            <td>{{ $tipo->regla_calculo_facturacion }}</td>
                            <td>{{ $tipo->grupo_descuento }}</td>
                            <td class="text-end pe-4">
                                <button wire:click="edit({{ $tipo->lt_id }})" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button wire:click="delete({{ $tipo->lt_id }})"
                                    onclick="confirm('¿Seguro que desea eliminar este tipo?') || event.stopImmediatePropagation()"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No se encontraron tipos de licencias.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $tipos->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="formModalLabel">
                        {{ $isEditMode ? 'Editar Tipo' : 'Nuevo Tipo de Licencia' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model="nombre">
                            @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Código Legacy</label>
                            <input type="text" class="form-control @error('codigo_legacy') is-invalid @enderror"
                                wire:model="codigo_legacy" placeholder="Ej: ART_14A">
                            @error('codigo_legacy') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Regla Cálculo (Facturación)</label>
                            <select class="form-select @error('regla_calculo_facturacion') is-invalid @enderror"
                                wire:model="regla_calculo_facturacion">
                                <option value="total">Total</option>
                                <option value="lao">LAO</option>
                                <option value="proporcional">Proporcional</option>
                                <option value="ninguno">Ninguno</option>
                            </select>
                            @error('regla_calculo_facturacion') <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Grupo Descuento</label>
                            <input type="text" class="form-control" wire:model="grupo_descuento"
                                placeholder="Ej: enfermedad, vacaciones...">
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="store" class="btn btn-primary px-4">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('show-modal', event => {
            var myModal = new bootstrap.Modal(document.getElementById('formModal'));
            myModal.show();
        });
        window.addEventListener('hide-modal', event => {
            var myModalEl = document.getElementById('formModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if (modal) {
                modal.hide();
            }
        });
    </script>
</div>