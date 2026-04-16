<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-8 flex items-center">
        <i class="bi bi-bar-chart-line text-indigo-600 mr-4"></i> Informes y Reportes
    </h2>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden mb-8 shadow-sm">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h5 class="text-lg font-bold text-slate-700 flex items-center italic">
                    <i class="bi bi-funnel mr-2 text-indigo-600"></i> Filtros de Búsqueda
                </h5>
                <button wire:click="limpiarFiltros" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-rose-500 transition-colors flex items-center">
                    <i class="bi bi-x-circle mr-2"></i> Limpiar Filtros
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Agente</label>
                    <select wire:model="agente_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer shadow-sm">
                        <option value="">-- Todos --</option>
                        @foreach($agentes as $age)
                            <option value="{{ $age->age_id }}">{{ $age->age_apell1 }}, {{ $age->age_nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tipo Licencia</label>
                    <select wire:model="lt_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer shadow-sm">
                        <option value="">-- Todos --</option>
                        @foreach($tipos_licencia as $tipo)
                            <option value="{{ $tipo->lt_id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Estado</label>
                    <select wire:model="estado" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer shadow-sm">
                        <option value="">-- Todos --</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>
                
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Desde</label>
                    <input type="date" wire:model="fecha_desde" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 shadow-sm">
                </div>
                
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Hasta</label>
                    <input type="date" wire:model="fecha_hasta" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-0 overflow-x-auto">
            @if($resultados->count() > 0)
                <div class="px-8 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center shadow-inner">
                    <span class="text-xs font-black text-indigo-600 uppercase tracking-widest italic leading-none">Resultados Encontrados: {{ $resultados->total() }}</span>
                </div>
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-4 w-20">ID</th>
                            <th class="px-8 py-4">Agente</th>
                            <th class="px-8 py-4">Tipo</th>
                            <th class="px-8 py-4">Período</th>
                            <th class="px-8 py-4">Estado</th>
                            <th class="px-8 py-4">Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 italic">
                        @foreach($resultados as $sol)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5 text-sm font-mono text-slate-400 group-hover:text-indigo-500 transition-colors">#{{ $sol->solicitud_id }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center mr-3 text-[10px] font-black text-indigo-700 shadow-sm">
                                            {{ substr($sol->agente->age_nombre, 0, 1) }}{{ substr($sol->agente->age_apell1, 0, 1) }}
                                        </div>
                                        <div class="font-bold text-slate-700">{{ optional($sol->agente)->age_apell1 }}, {{ optional($sol->agente)->age_nombre }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-tighter rounded-full shadow-sm">
                                        {{ optional($sol->tipoLicencia)->nombre }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-xs text-slate-500 leading-tight">
                                    <div class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($sol->fecha_inicio)->format('d/m/Y') }}</div>
                                    <div class="text-[9px] uppercase tracking-widest text-slate-400">al {{ \Carbon\Carbon::parse($sol->fecha_fin)->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    @php
                                        $clase = match(strtolower($sol->estado)) {
                                            'pendiente' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'aprobada'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'rechazada' => 'bg-rose-100 text-rose-800 border-rose-200',
                                            default     => 'bg-slate-100 text-slate-600 border-slate-200'
                                        };
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-xl border {{ $clase }} inline-flex items-center text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        <div class="w-1.5 h-1.5 rounded-full mr-2 {{ str_contains($clase, 'emerald') ? 'bg-emerald-500' : (str_contains($clase, 'amber') ? 'bg-amber-500' : 'bg-rose-500') }}"></div>
                                        {{ $sol->estado }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="text-[10px] text-slate-400 truncate max-w-[200px]" title="{{ $sol->motivo }}">"{{ Str::limit($sol->motivo, 40) }}"</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-32 opacity-30 italic">
                    <i class="bi bi-search text-7xl mb-6 block"></i>
                    <p class="font-black uppercase tracking-[0.2em] text-xs">Sin registros que coincidan</p>
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-8 flex justify-center">
        {{ $resultados->links() }}
    </div>
</div>
