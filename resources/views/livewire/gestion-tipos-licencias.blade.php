<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 mb-1 leading-tight tracking-tight italic">Tipos de Licencias</h2>
            <p class="text-slate-500 text-sm font-medium">Gestión de códigos legacy y reglas de cálculo de facturación</p>
        </div>
        <div>
            <button wire:click="create" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-200 transition-all flex items-center group">
                <i class="bi bi-plus-lg mr-2 transition-transform group-hover:rotate-90"></i> Nuevo Tipo de Licencia
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center mb-8 border border-emerald-200 animate__animated animate__fadeIn">
            <i class="bi bi-check-circle-fill text-xl mr-3"></i>
            <span class="flex-1 font-medium">{{ session('message') }}</span>
            <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('message', null)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-200 px-8 py-6">
            <div class="max-w-md relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                <input wire:model="search" type="text" class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm font-medium text-slate-700 shadow-sm" placeholder="Buscar por nombre de licencia...">
            </div>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                        <th class="px-8 py-5">Código Legacy</th>
                        <th class="px-8 py-5">Nombre / Concepto</th>
                        <th class="px-8 py-5">Regla Cálculo</th>
                        <th class="px-8 py-5">Grupo Descuento</th>
                        <th class="px-8 py-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 italic">
                    @forelse($tipos as $tipo)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-black bg-slate-100 text-slate-600 border border-slate-200 group-hover:bg-indigo-50 group-hover:text-indigo-700 group-hover:border-indigo-100 transition-all font-mono">
                                    {{ $tipo->codigo_legacy }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-800 text-lg tracking-tight">{{ $tipo->nombre }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-indigo-100 shadow-sm">
                                    {{ $tipo->regla_calculo_facturacion }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-500 font-medium italic">
                                {{ $tipo->grupo_descuento ?: 'N/A' }}
                            </td>
                            <td class="px-8 py-5 text-right space-x-2 whitespace-nowrap">
                                <button wire:click="edit({{ $tipo->lt_id }})" class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-transparent hover:border-indigo-100 group/btn shadow-sm" title="Editar">
                                    <i class="bi bi-pencil group-hover/btn:scale-125 transition-transform inline-block"></i>
                                </button>
                                <button wire:click="delete({{ $tipo->lt_id }})"
                                    onclick="confirm('¿Seguro que desea eliminar este tipo?') || event.stopImmediatePropagation()"
                                    class="p-2.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100 group/btn shadow-sm" title="Eliminar">
                                    <i class="bi bi-trash group-hover/btn:scale-125 transition-transform inline-block"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <i class="bi bi-inbox text-7xl text-slate-300 mb-6 animate__animated animate__pulse animate__infinite"></i>
                                    <h5 class="text-xl font-bold text-slate-800">No hay resultados</h5>
                                    <p class="text-slate-500">No se encontraron tipos de licencias con los criterios actuales.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-200">
            {{ $tipos->links() }}
        </div>
    </div>

    <!-- Modal (Bootstrap JS used for control, Tailwind for styling insides) -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content !border-0 !rounded-[2.5rem] shadow-2xl overflow-hidden bg-white/95 backdrop-blur-xl">
                <div class="bg-indigo-600 px-8 py-6 flex justify-between items-center text-white">
                    <h5 class="text-xl font-black italic tracking-wide flex items-center">
                        <i class="bi {{ $isEditMode ? 'bi-pencil-square' : 'bi-plus-circle' }} mr-3"></i>
                        {{ $isEditMode ? 'Editar Tipo' : 'Nuevo Tipo' }}
                    </h5>
                    <button type="button" class="p-2 hover:bg-white/10 rounded-full transition-all" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="p-8">
                    <form class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Nombre Completo</label>
                            <input type="text" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-bold placeholder-slate-300 shadow-sm" wire:model="nombre">
                            @error('nombre') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1 block italic animate__animated animate__fadeInLeft">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Código Legacy (Facturación)</label>
                            <input type="text" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-mono font-bold placeholder-slate-300 shadow-sm" wire:model="codigo_legacy" placeholder="Ej: ART_14A">
                            @error('codigo_legacy') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1 block italic animate__animated animate__fadeInLeft">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Regla de Cálculo</label>
                            <select class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-bold cursor-pointer shadow-sm" wire:model="regla_calculo_facturacion">
                                <option value="total">Calculo Total</option>
                                <option value="lao">Calculo LAO</option>
                                <option value="proporcional">Calculo Proporcional</option>
                                <option value="ninguno">Sin Calculo</option>
                            </select>
                            @error('regla_calculo_facturacion') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1 block italic animate__animated animate__fadeInLeft">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Grupo de Descuento (Etiqueta)</label>
                            <input type="text" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-bold placeholder-slate-300 shadow-sm" wire:model="grupo_descuento" placeholder="Ej: enfermedad, vacaciones...">
                        </div>
                    </form>
                </div>
                <div class="px-8 py-8 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
                    <button type="button" class="px-8 py-3 text-slate-500 hover:text-slate-800 font-black uppercase text-xs tracking-widest transition-all" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click.prevent="store" class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase text-xs tracking-widest rounded-2xl shadow-xl shadow-indigo-100 hover:shadow-indigo-200 transition-all active:scale-95">
                        {{ $isEditMode ? 'Guardar Cambios' : 'Crear Registro' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script shadow>
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
