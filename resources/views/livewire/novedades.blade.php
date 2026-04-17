<div class="mb-12">
    <div class="flex justify-between items-center mb-8">
        <h4 class="text-2xl font-bold text-slate-800 flex items-center">
            <i class="bi bi-megaphone-fill text-indigo-600 mr-3"></i>Novedades Institucionales
        </h4>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($novedades as $novedad)
            @php
                $tipo = $novedad->tipo ?? 'info';
                $config = match ($tipo) {
                    'danger' => [
                        'bg' => 'bg-rose-50',
                        'text' => 'text-rose-600',
                        'border' => 'border-rose-500',
                        'label' => 'Importante',
                        'icon' => 'bi-exclamation-triangle-fill',
                        'btn' => 'bg-rose-50 text-rose-600 hover:bg-rose-100'
                    ],
                    'warning' => [
                        'bg' => 'bg-amber-50',
                        'text' => 'text-amber-600',
                        'border' => 'border-amber-500',
                        'label' => 'Atención',
                        'icon' => 'bi-info-circle-fill',
                        'btn' => 'bg-amber-50 text-amber-600 hover:bg-amber-100'
                    ],
                    'success' => [
                        'bg' => 'bg-emerald-50',
                        'text' => 'text-emerald-600',
                        'border' => 'border-emerald-500',
                        'label' => 'Novedad',
                        'icon' => 'bi-check-circle-fill',
                        'btn' => 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100'
                    ],
                    default => [
                        'bg' => 'bg-indigo-50',
                        'text' => 'text-indigo-600',
                        'border' => 'border-indigo-500',
                        'label' => 'Información',
                        'icon' => 'bi-info-circle-fill',
                        'btn' => 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100'
                    ]
                };
            @endphp
            <div class="group h-full flex flex-col bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-200 overflow-hidden transition-all duration-300 border-t-4 {{ $config['border'] }}">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class="bi {{ $config['icon'] }} mr-2"></i> {{ $config['label'] }}
                        </span>
                        <span class="text-xs font-medium text-slate-400">
                            {{ \Carbon\Carbon::parse($novedad->nov_fecha_publicacion)->format('d/m/Y') }}
                        </span>
                    </div>
                    
                    <h5 class="text-lg font-bold text-slate-800 mb-3 group-hover:text-indigo-600 transition-colors">{{ $novedad->titulo }}</h5>
                    
                    <p class="text-slate-600 text-sm leading-relaxed mb-6 flex-grow">
                        {{ Str::limit($novedad->contenido, 120) }}
                    </p>
                    
                    <button wire:click="selectNovedad({{ $novedad->id }})" class="w-full py-2.5 rounded-xl font-bold flex items-center justify-center transition-all cursor-pointer {{ $config['btn'] }}">
                        Leer contenido <i class="bi bi-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal de Lectura (Tailwind native modal approach would be better, but staying with Bootstrap Modal for JS stability if requested, applying Tailwind styles inside) -->
    <div class="modal fade" id="modalLeerNovedad" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content !border-0 !rounded-[2rem] shadow-2xl overflow-hidden bg-white/90 backdrop-blur-xl">
                @if($selectedNovedad)
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <h4 class="text-2xl font-bold text-slate-800">{{ $selectedNovedad->titulo }}</h4>
                            <button type="button" class="p-2 hover:bg-slate-100 rounded-full transition-colors" data-bs-dismiss="modal" aria-label="Close">
                                <i class="bi bi-x-lg text-slate-400 text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center text-slate-500 text-sm mb-8 bg-slate-50 w-fit px-4 py-1.5 rounded-full">
                            <i class="bi bi-calendar-event mr-2 text-indigo-600"></i>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($selectedNovedad->nov_fecha_publicacion)->format('d \d\e F, Y') }}</span>
                        </div>
                        
                        <div class="text-slate-600 text-lg leading-relaxed whitespace-pre-wrap max-h-[60vh] overflow-y-auto pr-4 custom-scrollbar">
                            {{ $selectedNovedad->contenido }}
                        </div>
                        
                        <div class="mt-10 flex justify-end">
                            <button type="button" class="px-8 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl transition-all shadow-lg hover:shadow-slate-200" data-bs-dismiss="modal">Entendido</button>
                        </div>
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
