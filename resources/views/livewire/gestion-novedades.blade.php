<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 p-6 bg-white rounded-[2rem] shadow-sm border border-slate-100 italic">
        <div>
            <h2 class="text-3xl font-black text-slate-800 mb-1 leading-none italic uppercase tracking-tighter">Gestión de Novedades</h2>
            <p class="text-slate-500 text-sm font-medium italic">Administre los anuncios institucionales visibles para todos los agentes.</p>
        </div>
        <button wire:click="create" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase text-xs tracking-widest rounded-2xl shadow-lg shadow-indigo-100 transition-all flex items-center group active:scale-95">
            <i class="bi bi-plus-circle-fill text-lg mr-3 transition-transform group-hover:rotate-90"></i>
            <span>Nueva Novedad</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center mb-8 border border-emerald-200 animate__animated animate__fadeIn italic">
            <i class="bi bi-check-circle-fill text-xl mr-3"></i>
            <span class="flex-1 font-black uppercase text-xs tracking-wider">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden animate__animated animate__fadeInUp">
        <div class="bg-slate-50 border-b border-slate-200 px-8 py-6">
            <div class="max-w-md relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                <input wire:model="search" type="text" class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm font-medium text-slate-700 shadow-sm italic" placeholder="Buscar por título de noticia...">
            </div>
        </div>
        
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black italic">
                        <th class="px-8 py-5">Contenido Informativo</th>
                        <th class="px-8 py-5">Nivel de Alerta</th>
                        <th class="px-8 py-5 text-center">Estado Fix</th>
                        <th class="px-8 py-5 text-right">Acciones Directas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 italic font-medium">
                    @forelse($novedades as $novedad)
                        <tr class="hover:bg-slate-50/50 transition-colors group relative">
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <div class="font-black text-slate-800 text-lg tracking-tight leading-none mb-1 italic">{{ $novedad->nov_titulo }}</div>
                                    <div class="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2">
                                        <i class="bi bi-calendar-event mr-2"></i>
                                        Publicado el {{ \Carbon\Carbon::parse($novedad->nov_fecha_publicacion)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500 max-w-[400px] truncate italic font-medium">
                                        {{ \Illuminate\Support\Str::limit($novedad->nov_contenido_largo, 80) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $tipo = $novedad->nov_tipo ?? 'info';
                                    $config = ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-100', 'label' => 'General'];
                                    
                                    if($tipo == 'info') $config = ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-100', 'label' => 'Informativa'];
                                    if($tipo == 'warning') $config = ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'label' => 'Advertencia'];
                                    if($tipo == 'danger') $config = ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-100', 'label' => 'Urgente'];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }} shadow-sm italic">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex justify-center">
                                    <button wire:click="toggleFijada({{ $novedad->nov_id }})" 
                                        class="w-12 h-6 rounded-full p-1 transition-all duration-300 {{ $novedad->nov_fijada ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                        <div class="bg-white w-4 h-4 rounded-full shadow-sm transition-transform duration-300 {{ $novedad->nov_fijada ? 'translate-x-6' : 'translate-x-0' }}"></div>
                                    </button>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right space-x-2 whitespace-nowrap">
                                <button wire:click="edit({{ $novedad->nov_id }})" class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-transparent hover:border-indigo-100 group/btn shadow-sm" title="Editar">
                                    <i class="bi bi-pencil-square group-hover/btn:scale-125 transition-transform inline-block"></i>
                                </button>
                                <button onclick="confirm('¿Estás seguro de eliminar esta noticia?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $novedad->nov_id }})"
                                        class="p-2.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100 group/btn shadow-sm" title="Eliminar">
                                    <i class="bi bi-trash group-hover/btn:scale-125 transition-transform inline-block"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-32 text-center text-slate-400">
                                <i class="bi bi-megaphone text-6xl opacity-20 block mb-6"></i>
                                <span class="text-sm font-black uppercase tracking-widest italic">No hay novedades registradas</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-200">
            {{ $novedades->links() }}
        </div>
    </div>

    <!-- Modal Premium -->
    <div class="modal fade" id="modalNovedad" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content !border-0 !rounded-[2.5rem] shadow-2xl overflow-hidden bg-white/95 backdrop-blur-xl">
                <div class="bg-slate-800 px-8 py-6 flex justify-between items-center text-white italic">
                    <h5 class="text-xl font-black italic tracking-wide flex items-center uppercase">
                        <i class="bi {{ $isEditMode ? 'bi-pencil-square' : 'bi-plus-circle' }} mr-3 text-indigo-400"></i>
                        {{ $isEditMode ? 'Actualizar Datos' : 'Crear Comunicado' }}
                    </h5>
                    <button type="button" class="p-2 hover:bg-white/10 rounded-full transition-all" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="p-8 italic">
                    <form class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Título de la Novedad</label>
                            <input type="text" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-bold placeholder-slate-300 shadow-sm italic" wire:model.defer="titulo" placeholder="Ej: Campaña Vacunación">
                            @error('titulo') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1 block italic">{{ $message }}</span> @enderror
                        </div>
                       
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Fecha Publicación</label>
                                <input type="datetime-local" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-bold shadow-sm italic" wire:model.defer="fecha_publicacion">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Nivel / Estilo</label>
                                <select class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-bold cursor-pointer shadow-sm italic" wire:model.defer="tipo">
                                    <option value="info">💬 Informativa</option>
                                    <option value="warning">⚠️ Advertencia</option>
                                    <option value="danger">🚨 Urgente</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="bi bi-pin-angle-fill text-rose-500 text-xl mr-3"></i>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-700 leading-none mb-1">Destacar Noticia</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase">Mantiene la noticia arriba</div>
                                </div>
                            </div>
                            <button type="button" wire:click="$set('fijada', {{ !$fijada ? 'true' : 'false' }})" 
                                class="w-12 h-6 rounded-full p-1 transition-all duration-300 {{ $fijada ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                <div class="bg-white w-4 h-4 rounded-full shadow-sm transition-transform duration-300 {{ $fijada ? 'translate-x-6' : 'translate-x-0' }}"></div>
                            </button>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Desarrollo del Mensaje</label>
                            <textarea class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-700 font-black italic placeholder-slate-300 shadow-sm" rows="5" wire:model.defer="contenido"></textarea>
                            @error('contenido') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1 block italic">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="px-8 py-8 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
                    <button type="button" class="px-8 py-3 text-slate-400 hover:text-slate-800 font-black uppercase text-[10px] tracking-widest transition-all italic" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click="store" class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase text-xs tracking-widest rounded-2xl shadow-xl shadow-indigo-100 hover:shadow-indigo-200 transition-all active:scale-95 italic">
                        {{ $isEditMode ? 'Guardar Cambios' : 'Publicar Ahora' }}
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
