<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-8 flex items-center">
        <i class="bi bi-person-lines-fill text-indigo-600 mr-4"></i> Reportes RRHH Especializados
    </h2>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden mb-8 shadow-sm">
        <div class="p-8">
            <h5 class="text-lg font-bold text-slate-700 mb-6 flex items-center italic">
                <i class="bi bi-funnel mr-2 text-indigo-600"></i> Parámetros del Reporte
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 items-end">
                <div class="lg:col-span-3 space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tipo de Reporte</label>
                    <select wire:model="reporteTipo" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer shadow-sm">
                        <option value="ausentismo">📊 Ausentismo Global</option>
                        <option value="tardanzas">⏰ Registro de Tardanzas</option>
                        <option value="licencias">🏥 Control de Licencias</option>
                    </select>
                </div>

                <div class="lg:col-span-2 space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Fecha Inicio</label>
                    <input type="date" wire:model="fechaInicio" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 shadow-sm">
                </div>

                <div class="lg:col-span-2 space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Fecha Fin</label>
                    <input type="date" wire:model="fechaFin" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 shadow-sm">
                </div>

                <div class="lg:col-span-2 space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Departamento</label>
                    <select wire:model="departamentoId" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer shadow-sm">
                        <option value="">Todos los Deptos</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->tdep_id }}">{{ $dep->tdep_nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2 space-y-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Agente</label>
                    <select wire:model="agenteId" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer shadow-sm">
                        <option value="">Todos los Agentes</option>
                        @foreach($agentes as $ag)
                            <option value="{{ $ag->age_id }}">{{ $ag->age_apell1 }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-1">
                    <button wire:click="generarReporte" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg hover:shadow-indigo-100 transition-all flex items-center justify-center">
                        <i class="bi bi-search font-bold"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="px-8 py-5 bg-slate-800 border-b border-slate-700 flex items-center justify-between">
            <h5 class="text-white font-bold text-lg flex items-center italic tracking-wide leading-none">
                <i class="bi bi-bar-chart mr-3 text-indigo-400"></i> Visualización de Datos
            </h5>
            <span class="px-3 py-1 bg-indigo-500 text-white rounded-full text-[10px] font-black uppercase tracking-tighter shadow-lg">{{ strtoupper($reporteTipo) }}</span>
        </div>
        
        <div class="p-0 overflow-x-auto">
            @if($reporteTipo == 'ausentismo')
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-5">Agente</th>
                            <th class="px-8 py-5">Departamento</th>
                            <th class="px-8 py-5">Total Ausencias</th>
                            <th class="px-8 py-5">Desglose</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 italic">
                        @forelse($resultados as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-800">{{ $item->age_apell1 }}, {{ $item->age_nombre }}</div>
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-400 font-bold uppercase tracking-widest">ID: {{ $item->tdep_id }}</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-black bg-rose-50 text-rose-700 border border-rose-100 shadow-sm">
                                        {{ $item->total_ausencias }} Faltas
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-xs text-slate-500 font-medium">
                                    {{ $item->tipos_ausencia ?: 'Sin especificar' }}
                                </td>
                            </tr>
                        @empty
                            <tr>@include('livewire.reportes.empty-state')</tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($reporteTipo == 'tardanzas')
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-5">Agente</th>
                            <th class="px-8 py-5 text-center">Fecha</th>
                            <th class="px-8 py-5 text-center">Fichada Real</th>
                            <th class="px-8 py-5 text-center">Horario Teórico</th>
                            <th class="px-8 py-5 text-right">Incidencia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 italic">
                        @forelse($resultados as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-800">{{ $item->age_apell1 }}, {{ $item->age_nombre }}</div>
                                </td>
                                <td class="px-8 py-5 text-center text-sm font-bold text-slate-500">
                                    {{ \Carbon\Carbon::parse($item->fich_fecha)->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="text-lg font-black text-rose-600 font-mono">{{ $item->fich_hora }}</span>
                                </td>
                                <td class="px-8 py-5 text-center group-hover:scale-105 transition-transform font-mono text-slate-400">
                                    {{ $item->hora_entrada }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 text-[10px] font-black uppercase tracking-widest rounded-lg border border-amber-200 shadow-sm">Tardanza</span>
                                </td>
                            </tr>
                        @empty
                            <tr>@include('livewire.reportes.empty-state')</tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($reporteTipo == 'licencias')
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-5">Agente</th>
                            <th class="px-8 py-5">Tipo Licencia</th>
                            <th class="px-8 py-5">Periodo Vigencia</th>
                            <th class="px-8 py-5 text-right">Estado Actual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 italic">
                        @forelse($resultados as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-800">{{ $item->agente->age_apell1 }}, {{ $item->agente->age_nombre }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-tighter rounded-full border border-indigo-100 shadow-sm">
                                        {{ optional($item->tipoLicencia)->nombre }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col text-sm text-slate-600 leading-tight">
                                        <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y') }}</span>
                                        <span class="text-[9px] text-slate-400 uppercase tracking-widest font-black">al {{ \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @php
                                        $clase = match ($item->estado) {
                                            'PENDIENTE' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'APROBADA' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'RECHAZADA' => 'bg-rose-100 text-rose-800 border-rose-200',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-xl border {{ $clase }} text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        {{ $item->estado }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            {{-- Consider making a shared empty-state if it repeats --}}
                            <tr>
                                <td colspan="4" class="px-8 py-24 text-center opacity-30 italic">
                                    <i class="bi bi-inbox text-7xl mb-6 block"></i>
                                    <p class="font-black uppercase tracking-[0.2em] text-xs">Sin información para este filtro</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
