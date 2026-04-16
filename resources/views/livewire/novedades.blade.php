<div class="row mb-5">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 fw-bold text-primary">
                <i class="bi bi-megaphone-fill me-2"></i>Novedades Institucionales
            </h4>
        </div>
        
        <div class="row g-4">
            @foreach($novedades as $novedad)
                @php
                    $tipo = $novedad->nov_tipo ?? 'info';
                    $config = match ($tipo) {
                        'danger' => [
                            'bg' => 'rgba(239, 68, 68, 0.1)',
                            'text' => '#ef4444',
                            'border' => '#ef4444',
                            'label' => 'Importante',
                            'icon' => 'bi-exclamation-triangle-fill'
                        ],
                        'warning' => [
                            'bg' => 'rgba(245, 158, 11, 0.1)',
                            'text' => '#f59e0b',
                            'border' => '#f59e0b',
                            'label' => 'Atención',
                            'icon' => 'bi-info-circle-fill'
                        ],
                        'success' => [
                            'bg' => 'rgba(16, 185, 129, 0.1)',
                            'text' => '#10b981',
                            'border' => '#10b981',
                            'label' => 'Novedad',
                            'icon' => 'bi-check-circle-fill'
                        ],
                        default => [
                            'bg' => 'rgba(59, 130, 246, 0.1)',
                            'text' => '#3b82f6',
                            'border' => '#3b82f6',
                            'label' => 'Información',
                            'icon' => 'bi-info-circle-fill'
                        ]
                    };
                @endphp
                <div class="col-md-4 mb-3">
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden h-100 border-0 shadow-sm animate__animated animate__fadeInUp" style="border-top: 4px solid {{ $config['border'] }} !important; animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge rounded-pill px-3 py-2 d-flex align-items-center" style="background: {{ $config['bg'] }}; color: {{ $config['text'] }}; font-weight: 600; font-size: 0.75rem;">
                                    <i class="bi {{ $config['icon'] }} me-2"></i> {{ $config['label'] }}
                                </span>
                                <small class="text-muted fw-medium">
                                    {{ \Carbon\Carbon::parse($novedad->nov_fecha_publicacion)->format('d/m/Y') }}
                                </small>
                            </div>
                            
                            <h5 class="card-title fw-bold text-dark mb-3">{{ $novedad->nov_titulo }}</h5>
                            
                            <p class="card-text text-secondary mb-4 flex-grow-1" style="font-size: 0.95rem; line-height: 1.6;">
                                {{ Str::limit($novedad->nov_contenido_largo, 120) }}
                            </p>
                            
                            <button wire:click="selectNovedad({{ $novedad->nov_id }})"
                                class="btn w-100 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center transition-all"
                                style="background: {{ $config['bg'] }}; color: {{ $config['text'] }}; border: none;">
                                Leer contenido <i class="bi bi-arrow-right ms-2 mt-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal de Lectura Premium -->
    <div class="modal fade" id="modalLeerNovedad" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 glass-effect" style="border-radius: 24px; overflow: hidden;">
                @if($selectedNovedad)
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h4 class="modal-title fw-bold text-dark">{{ $selectedNovedad->nov_titulo }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center mb-4 text-muted small">
                            <i class="bi bi-calendar-event me-2"></i>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($selectedNovedad->nov_fecha_publicacion)->format('d \d\e F, Y') }}</span>
                        </div>
                        <div class="novedad-contenido" style="white-space: pre-wrap; font-size: 1.1rem; line-height: 1.8; color: #4b5563;">
                            {{ $selectedNovedad->nov_contenido_largo }}
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Entendido</button>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <script>
        window.addEventListener('open-novedad-modal', event => {
            var myModal = new bootstrap.Modal(document.getElementById('modalLeerNovedad'));
            myModal.show();
        });
    </script>
</div>


