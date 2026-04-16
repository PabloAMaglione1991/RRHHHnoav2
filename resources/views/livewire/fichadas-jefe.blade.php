<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-8 flex items-center">
        <i class="bi bi-people text-indigo-600 mr-4"></i> Asistencia Agentes a Cargo
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Panel Izquierdo: Buscador de Agentes -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] shadow-lg border border-slate-200 overflow-hidden sticky top-8">
                <div class="bg-indigo-600 px-6 py-4">
                    <h5 class="text-white font-bold text-sm flex items-center uppercase tracking-wider">
                        <i class="bi bi-search mr-2"></i> Buscar Agente
                    </h5>
                </div>
                <div class="p-6">
                    <div class="relative mb-6">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" wire:model="search" class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm placeholder-slate-400 font-medium" placeholder="Nombre o DNI...">
                    </div>
                    
                    <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($agentes as $ag)
                            <button wire:click="seleccionarAgente({{ $ag->age_id }})"
                                class="w-full text-left p-4 rounded-2xl transition-all duration-200 border-2 {{ $agente_id == $ag->age_id ? 'bg-indigo-600 border-indigo-600 shadow-indigo-200 shadow-lg' : 'bg-white border-transparent hover:bg-slate-50 hover:border-slate-100' }}">
                                <div class="font-bold text-sm {{ $agente_id == $ag->age_id ? 'text-white' : 'text-slate-800' }}">{{ $ag->age_apell1 }}, {{ $ag->age_nombre }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-widest mt-1 {{ $agente_id == $ag->age_id ? 'text-indigo-100' : 'text-slate-400' }}">DNI: {{ $ag->age_numdoc }}</div>
                            </button>
                        @empty
                            <div class="flex flex-col items-center justify-center p-8 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                <i class="bi bi-person-x text-4xl text-slate-300 mb-2"></i>
                                <p class="text-xs font-bold text-slate-500 uppercase">Sin resultados</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Vista de Calendario -->
        <div class="lg:col-span-3">
            @if($datosAgente)
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden mb-8 animate__animated animate__fadeIn">
                    <div class="p-8">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 text-indigo-700 w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mr-5 shadow-inner">
                                    {{ substr($datosAgente->age_nombre, 0, 1) }}{{ substr($datosAgente->age_apell1, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-2xl font-bold text-slate-800">
                                        {{ $datosAgente->age_nombre }} {{ $datosAgente->age_apell1 }}
                                    </h4>
                                    <div class="flex items-center text-slate-500 text-sm mt-1">
                                        <i class="bi bi-calendar-event mr-2 text-indigo-500"></i>
                                        Período: <span class="ml-1 font-bold text-indigo-600 uppercase">{{ \Carbon\Carbon::createFromDate($anioActual, $mesActual, 1)->translatedFormat('F Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="px-5 py-2 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold tracking-widest uppercase border border-indigo-100">
                                    Vista Mensual
                                </div>
                                <select wire:model="mesActual" class="px-4 py-2 border-none bg-slate-100 rounded-full text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none transition-all cursor-pointer">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                                    @endforeach
                                </select>
                                <select wire:model="anioActual" class="px-4 py-2 border-none bg-slate-100 rounded-full text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none transition-all cursor-pointer">
                                    @foreach(range(date('Y')-5, date('Y')+1) as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
                    <div class="p-0 overflow-x-auto">
                        <table class="w-full text-center table-fixed border-collapse min-w-[800px]">
                            <thead class="bg-slate-800 text-white">
                                <tr>
                                    <th class="py-5 font-bold text-xs uppercase tracking-[0.2em] border-r border-slate-700/50">Lun</th>
                                    <th class="py-5 font-bold text-xs uppercase tracking-[0.2em] border-r border-slate-700/50">Mar</th>
                                    <th class="py-5 font-bold text-xs uppercase tracking-[0.2em] border-r border-slate-700/50">Mié</th>
                                    <th class="py-5 font-bold text-xs uppercase tracking-[0.2em] border-r border-slate-700/50">Jue</th>
                                    <th class="py-5 font-bold text-xs uppercase tracking-[0.2em] border-r border-slate-700/50">Vie</th>
                                    <th class="py-5 font-bold text-xs uppercase tracking-[0.2em] border-r border-slate-700/50 text-amber-400">Sáb</th>
                                    <th class="py-5 font-bold text-xs uppercase tracking-[0.2em] text-rose-400">Dom</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($calendario as $semana)
                                    <tr class="h-32">
                                        @foreach($semana as $dia)
                                            @php
                                                $bgClass = $dia['es_mes_actual'] ? 'bg-white hover:bg-slate-50' : 'bg-slate-50/50 opacity-40 grayscale';
                                                $borderClass = "border-r border-slate-100 last:border-r-0";
                                            @endphp
                                            <td class="{{ $bgClass }} {{ $borderClass }} relative p-4 align-top transition-colors duration-200">
                                                <span class="block text-left font-bold text-slate-500 mb-2 text-lg">{{ $dia['dia'] }}</span>
                                                <div class="flex flex-col gap-1.5 overflow-y-auto pr-1">
                                                    @foreach($dia['fichadas'] as $fichada)
                                                        @php
                                                            $colorClass = $fichada['tipo']=='E' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100';
                                                        @endphp
                                                        <div class="px-2 py-1.5 rounded-lg border {{ $colorClass }} text-[10px] font-bold flex justify-between items-center shadow-sm">
                                                            <span class="opacity-70 uppercase tracking-tighter">{{ $fichada['tipo'] == 'E' ? 'Entrada' : 'Salida' }}</span>
                                                            <span class="font-mono">{{ $fichada['hora'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="h-full min-h-[500px] flex flex-col items-center justify-center p-12 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 text-slate-400 shadow-inner group">
                    <div class="text-center animate__animated animate__pulse animate__infinite">
                        <div class="w-24 h-24 bg-indigo-50 text-indigo-300 rounded-full flex items-center justify-center mx-auto mb-8 transition-transform group-hover:scale-110">
                            <i class="bi bi-people text-5xl"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-700 mb-3">Gestión de Asistencia</h4>
                        <p class="max-w-md mx-auto text-slate-500 font-medium">Seleccione un agente del panel de la izquierda para desplegar sus fichadas mensuales y realizar el seguimiento de su jornada laboral.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
