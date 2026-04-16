<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-8 flex items-center">
        <i class="bi bi-gear-wide-connected text-indigo-600 mr-4"></i> Configuración del Sistema
    </h2>

    @if (session()->has('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center mb-8 border border-emerald-200 animate__animated animate__fadeIn">
            <i class="bi bi-check-circle-fill text-xl mr-3"></i>
            <span class="flex-1 font-medium">{{ session('success') }}</span>
            <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('success', null)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl flex items-center mb-8 border border-rose-200 animate__animated animate__fadeIn">
            <i class="bi bi-exclamation-triangle-fill text-xl mr-3"></i>
            <span class="flex-1 font-medium">{{ session('error') }}</span>
            <button type="button" class="text-rose-500 hover:text-rose-700 focus:outline-none" wire:click="$set('error', null)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden shadow-sm animate__animated animate__fadeInUp">
        <div class="bg-indigo-600 px-8 py-5 flex items-center shadow-lg">
            <h5 class="text-white font-bold text-lg flex items-center italic tracking-wide leading-none">
                <i class="bi bi-toggles mr-3"></i> Gestión de Módulos (Sidebar)
            </h5>
        </div>
        <div class="p-8">
            <p class="text-slate-500 mb-8 font-medium italic">Active o desactive los módulos que estarán visibles en la barra lateral para todos los usuarios del sistema.</p>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black italic">
                            <th class="px-8 py-5 w-20">ID</th>
                            <th class="px-8 py-5">Módulo Funcional</th>
                            <th class="px-8 py-5">Ruta de Sistema (Clave)</th>
                            <th class="px-8 py-5">Estado</th>
                            <th class="px-8 py-5 text-center">Visibilidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 italic">
                        @foreach($modulos as $modulo)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5 text-sm font-mono text-slate-400">#{{ $modulo->modulo_id }}</td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors leading-none italic">{{ $modulo->modulo_nombre }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-mono font-bold rounded-lg border border-slate-200 uppercase tracking-tighter">
                                        {{ $modulo->modulo_clave }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($modulo->modulo_activo)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm uppercase italic">Activo</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-400 border border-slate-200 uppercase italic">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex justify-center">
                                        @php
                                            $disabled = ($modulo->modulo_clave == 'dashboard' || $modulo->modulo_clave == 'configuracion');
                                        @endphp
                                        <button 
                                            wire:click="toggleModulo({{ $modulo->modulo_id }})" 
                                            class="w-12 h-6 rounded-full p-1 transition-all duration-300 {{ $modulo->modulo_activo ? 'bg-indigo-600' : 'bg-slate-200' }} {{ $disabled ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer' }}"
                                            @if($disabled) disabled @endif
                                        >
                                            <div class="bg-white w-4 h-4 rounded-full shadow-sm transition-transform duration-300 {{ $modulo->modulo_activo ? 'translate-x-6' : 'translate-x-0' }}"></div>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
