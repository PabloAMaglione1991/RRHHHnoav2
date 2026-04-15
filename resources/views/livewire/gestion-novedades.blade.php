<div>
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm border-start border-5 border-primary">
        <div>
            <h2 class="text-primary fw-bold mb-0">Gestión de Novedades</h2>
            <p class="text-muted mb-0 small">Administre los anuncios institucionales visible para todos los agentes.</p>
        </div>
        <button wire:click="create" class="btn btn-primary btn-lg px-4 shadow rounded-pill fw-bold d-flex align-items-center gap-2 hover-scale transition-all">
            <i class="bi bi-plus-circle-fill fs-5"></i>
            <span>Nueva Novedad</span>
        </button>
    </div>


    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="card card-premium shadow-sm mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input wire:model="search" type="text" class="form-control border-start-0 ps-0" placeholder="Buscar por título...">
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-premium text-nowrap align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Título</th>
                            <th>Tipo</th>
                            <th>Publicación</th>
                            <th class="text-center">Fijada</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($novedades as $novedad)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $novedad->nov_titulo }}</div>
                                    <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                        {{ Str::limit($novedad->nov_contenido_largo, 50) }}
                                    </small>
                                </td>
                                <td>
                                    @php
                                        // nov_tipo puede ser null si no corrieron el SQL, fallback a info
                                        $tipo = $novedad->nov_tipo ?? 'info';
                                        $badgeClass = match($tipo) {
                                            'info' => 'bg-info',
                                            'warning' => 'bg-warning text-dark',
                                            'danger' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        $tipoLabel = match($tipo) {
                                            'info' => 'Informativa',
                                            'warning' => 'Advertencia',
                                            'danger' => 'Urgente',
                                            default => 'General'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $badgeClass }}">{{ $tipoLabel }}</span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar-event me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($novedad->nov_fecha_publicacion)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               wire:click="toggleFijada({{ $novedad->nov_id }})"
                                               {{ $novedad->nov_fijada ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button wire:click="edit({{ $novedad->nov_id }})" class="btn btn-link text-primary p-0 me-3" title="Editar">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>
                                    <button onclick="confirm('¿Estás seguro de eliminar esta noticia?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $novedad->nov_id }})"
                                            class="btn btn-link text-danger p-0" title="Eliminar">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    No hay novedades registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
           
            <div class="mt-4 px-3">
                {{ $novedades->links() }}
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalNovedad" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">{{ $isEditMode ? 'Editar Novedad' : 'Nueva Campaña / Novedad' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Título</label>
                            <input type="text" class="form-control" wire:model.defer="titulo" placeholder="Ej: Campaña Vacunación">
                            @error('titulo') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                       
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha y Hora Publicación</label>
                            <input type="datetime-local" class="form-control" wire:model.defer="fecha_publicacion">
                            @error('fecha_publicacion') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-bold">Nivel de Importancia</label>
                            <select class="form-select" wire:model.defer="tipo">
                                <option value="info">Informativa (Azul)</option>
                                <option value="warning">Advertencia (Amarillo)</option>
                                <option value="danger">Urgente (Rojo)</option>
                            </select>
                            @error('tipo') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>


                        <div class="mb-3 form-check form-switch bg-light p-3 rounded ms-1">
                            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="checkFijada" wire:model="fijada">
                            <label class="form-check-label fw-bold" for="checkFijada">
                                <i class="bi bi-pin-angle-fill text-danger me-1"></i> Fijar al principio
                            </label>
                            <div class="form-text mt-1 ms-4">Las novedades fijadas aparecen siempre primero en el Dashboard.</div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-bold">Contenido</label>
                            <textarea class="form-control" rows="5" wire:model.defer="contenido" placeholder="Describa el detalle de la novedad..."></textarea>
                            @error('contenido') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary px-4" wire:click="store">
                        {{ $isEditMode ? 'Guardar Cambios' : 'Publicar Novedad' }}
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        window.addEventListener('show-modal', event => {
            var myModal = new bootstrap.Modal(document.getElementById('modalNovedad'));
            myModal.show();
        });


        window.addEventListener('hide-modal', event => {
            var el = document.getElementById('modalNovedad');
            var modal = bootstrap.Modal.getInstance(el);
            if (modal) {
                modal.hide();
            }
        });
    </script>
</div>




