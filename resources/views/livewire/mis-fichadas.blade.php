<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-6">Mis Fichadas</h2>

    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg flex items-center mb-6 border border-emerald-200" role="alert">
            <i class="bi bi-check-circle-fill text-xl mr-3"></i>
            <span class="flex-1 font-medium">{{ session('message') }}</span>
            <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('message', null)">
                <i class="bi bi-x-Lg"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-8">
        <div class="p-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="w-full md:w-auto">
                    <h4 class="text-xl text-indigo-700 font-bold capitalize flex items-center">
                        <i class="bi bi-calendar-month text-2xl mr-2"></i> {{ \Carbon\Carbon::createFromDate($anio, $mes, 1)->translatedFormat('F Y') }}
                    </h4>
                </div>
                <div class="w-full md:w-auto flex flex-wrap items-center justify-start md:justify-end gap-3">
                    <button wire:click="sincronizar" wire:loading.attr="disabled" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors flex items-center shadow-sm disabled:opacity-75 disabled:cursor-not-allowed cursor-pointer">
                        <span wire:loading.remove wire:target="sincronizar"><i class="bi bi-arrow-repeat mr-2"></i>Sincronizar Fichadas</span>
                        <span wire:loading wire:target="sincronizar" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Sincronizando...
                        </span>
                    </button>
                    
                    <select wire:model="mes" class="px-3 py-2 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer bg-white">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                    
                    <select wire:model="anio" class="px-3 py-2 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer bg-white">
                        @foreach(range(date('Y')-5, date('Y')+1) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                    
                    <button wire:click="hoy" class="px-4 py-2 border border-indigo-600 text-indigo-700 hover:bg-indigo-50 font-medium rounded-lg transition-colors shadow-sm bg-white">
                        Hoy
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="p-0 overflow-x-auto">
            <table class="w-full min-w-[800px] text-center table-fixed border-collapse">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="py-4 border-b border-r border-indigo-700 font-semibold w-[14.28%]">Lun</th>
                        <th class="py-4 border-b border-r border-indigo-700 font-semibold w-[14.28%]">Mar</th>
                        <th class="py-4 border-b border-r border-indigo-700 font-semibold w-[14.28%]">Mié</th>
                        <th class="py-4 border-b border-r border-indigo-700 font-semibold w-[14.28%]">Jue</th>
                        <th class="py-4 border-b border-r border-indigo-700 font-semibold w-[14.28%]">Vie</th>
                        <th class="py-4 border-b border-r border-indigo-700 font-semibold text-yellow-300 w-[14.28%]">Sáb</th>
                        <th class="py-4 border-b border-indigo-700 font-semibold text-red-300 w-[14.28%]">Dom</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($calendario as $semana)
                        <tr class="h-32">
                            @foreach($semana as $dia)
                                @php
                                    $esHoy = $dia['fecha'] == date('Y-m-d');
                                    $baseClass = "relative p-3 align-top border-r border-slate-200 last:border-r-0 transition-colors";
                                    $bgClass = $esHoy ? 'bg-indigo-50 border-2 !border-indigo-500 z-10 shadow-inner' : ($dia['es_mes_actual'] ? 'bg-white hover:bg-slate-50' : 'bg-slate-50 text-slate-400');
                                @endphp
                                <td class="{{ $baseClass }} {{ $bgClass }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-bold text-lg {{ $esHoy ? 'text-indigo-700' : 'text-slate-700' }}">{{ $dia['dia'] }}</span>
                                        @if($dia['horas_trabajadas'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                {{ $dia['horas_trabajadas'] }}hs
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="text-left text-sm flex flex-col gap-1">
                                        @foreach($dia['fichadas'] as $fichada)
                                            <div class="text-slate-600 bg-slate-100/50 rounded px-1">
                                                @if($fichada['tipo'] == 'E')
                                                    <span class="text-emerald-600 font-bold">E:</span> {{ \Carbon\Carbon::parse($fichada['hora'])->format('H:i') }}
                                                @else
                                                    <span class="text-rose-600 font-bold">S:</span> {{ \Carbon\Carbon::parse($fichada['hora'])->format('H:i') }}
                                                @endif
                                            </div>
                                        @endforeach
                                        
                                        @if($dia['inasistencia'])
                                             <div class="inline-block w-full text-center px-2 py-1 bg-rose-100 text-rose-800 text-xs font-semibold rounded truncate" title="Inasistencia">Ausente</div>
                                        @endif
                                        @if($dia['licencia'])
                                             <div class="inline-block w-full text-center px-2 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded truncate" title="{{ $dia['licencia'] }}">{{ $dia['licencia'] }}</div>
                                        @endif
                                        @if($dia['feriado'])
                                             <div class="inline-block w-full text-center px-2 py-1 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded truncate" title="Feriado">Feriado</div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
