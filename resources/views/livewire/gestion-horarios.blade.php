<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-8 flex items-center">
        <i class="bi bi-clock-history text-indigo-600 mr-4"></i> Gestión de Horarios
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Lista de Horarios -->
        <div class="lg:col-span-5 h-full">
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden flex flex-col h-full">
                <div class="bg-indigo-600 px-8 py-5 flex justify-between items-center shrink-0">
                    <h5 class="text-white font-bold text-lg flex items-center tracking-wide italic leading-none">
                        <i class="bi bi-list-task mr-3"></i> Horarios Definidos
                    </h5>
                    <button wire:click="resetInput" class="p-2 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all shadow-sm group">
                        <i class="bi bi-plus-lg transition-transform group-hover:rotate-90 block"></i>
                    </button>
                </div>
                
                <div class="flex-grow overflow-y-auto pr-2 custom-scrollbar p-4 space-y-3 max-h-[700px]">
                    @forelse($horarios as $hor)
                        <div class="p-5 rounded-2xl border-2 transition-all duration-300 flex justify-between items-center {{ $horario_id == $hor->id ? 'bg-indigo-50 border-indigo-500 shadow-md' : 'bg-white border-slate-100 hover:border-indigo-200 hover:shadow-sm' }} group">
                            <div class="flex-1">
                                <div class="font-black text-slate-800 text-lg italic leading-tight mb-1 {{ $horario_id == $hor->id ? 'text-indigo-700' : '' }}">{{ $hor->descripcion }}</div>
                                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">ID: {{ $hor->id }}</div>
                            </div>
                            <button wire:click="edit({{ $hor->id }})" class="w-10 h-10 flex items-center justify-center rounded-xl transition-all {{ $horario_id == $hor->id ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-50 text-slate-400 hover:bg-indigo-100 hover:text-indigo-600' }}">
                                <i class="bi bi-pencil-fill text-sm"></i>
                            </button>
                        </div>
                    @empty
                        <div class="py-20 text-center opacity-30 italic">
                            <i class="bi bi-calendar-x text-6xl mb-4 block"></i>
                            <p class="font-bold uppercase tracking-widest text-xs">Sin horarios definidos</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden sticky top-8 animate__animated animate__fadeInRight">
                <div class="bg-slate-800 border-b border-slate-700 px-8 py-5 flex items-center justify-between">
                    <h5 class="text-white font-bold text-lg flex items-center italic tracking-wide">
                        @if($horario_id)
                            <i class="bi bi-pencil-square text-amber-400 mr-3 animate-pulse"></i> Editando Horario
                        @else
                            <i class="bi bi-plus-square text-emerald-400 mr-3"></i> Nuevo Horario
                        @endif
                    </h5>
                    @if($horario_id)
                        <span class="px-3 py-1 bg-amber-400 text-slate-900 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-xl">Modo Edición</span>
                    @endif
                </div>
                
                <div class="p-8">
                    @if (session()->has('message'))
                        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center mb-8 border border-emerald-200 animate__animated animate__fadeIn">
                            <i class="bi bi-check-circle-fill text-xl mr-3"></i>
                            <span class="flex-1 font-medium">{{ session('message') }}</span>
                            <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('message', null)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    <div class="mb-8">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-3 px-1">Descripción del Horario</label>
                        <input type="text" wire:model="descripcion" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-[1.5rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-slate-800 font-black text-xl italic placeholder-slate-300 shadow-sm" placeholder="Ej: Lunes a Viernes 08-16hs">
                        @error('descripcion') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-4 block italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-slate-50/50 rounded-[2rem] p-8 border border-slate-100 mb-8">
                        <h6 class="text-indigo-700 font-black uppercase tracking-[0.2em] text-xs mb-6 flex items-center italic">
                            <i class="bi bi-clock-history mr-3 text-lg"></i> Configuración de Jornada Semanal
                        </h6>
                        <div class="space-y-4">
                            @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia)
                                <div class="grid grid-cols-12 gap-4 items-center group">
                                    <div class="col-span-4 font-black text-slate-600 text-sm uppercase tracking-wider italic pr-4 border-r border-slate-200 transition-colors group-hover:text-indigo-600 group-hover:border-indigo-200">
                                        {{ $dia }}
                                    </div>
                                    <div class="col-span-4 px-2">
                                        <input type="time" wire:model="{{ $dia }}_entrada" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm font-mono text-xs font-bold text-slate-700">
                                    </div>
                                    <div class="col-span-4 px-2">
                                        <input type="time" wire:model="{{ $dia }}_salida" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm font-mono text-xs font-bold text-slate-700">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-10">
                        @if($horario_id)
                            <button wire:click="resetInput" class="px-8 py-3 bg-white text-slate-500 hover:text-slate-800 font-black uppercase text-xs tracking-widest transition-all">
                                Cancelar
                            </button>
                        @endif
                        <button wire:click="store" class="px-12 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase text-xs tracking-[0.2em] rounded-[1.5rem] shadow-2xl shadow-indigo-100 hover:shadow-indigo-200 transition-all active:scale-95 flex items-center">
                            @if($horario_id) <i class="bi bi-cloud-check mr-3 text-lg"></i> Actualizar @else <i class="bi bi-plus-circle mr-3 text-lg"></i> Guardar @endif Horario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
