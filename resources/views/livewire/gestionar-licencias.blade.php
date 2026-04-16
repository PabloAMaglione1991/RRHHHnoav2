<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-8 flex items-center">
        <i class="bi bi-file-earmark-medical text-indigo-600 mr-4"></i> Gestionar Licencias (RRHH)
    </h2>

    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center mb-8 border border-emerald-200 animate__animated animate__fadeIn">
            <i class="bi bi-check-circle-fill text-xl mr-3"></i>
            <span class="flex-1 font-medium">{{ session('message') }}</span>
            <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('message', null)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-slate-800 border-b border-slate-700 px-8 py-5 flex items-center justify-between">
            <h5 class="text-white font-bold text-lg flex items-center italic tracking-wide">
                <i class="bi bi-hourglass-split mr-3 text-amber-400"></i> Solicitudes Pendientes
            </h5>
            <span class="px-3 py-1 bg-amber-400 text-slate-900 rounded-full text-[10px] font-black uppercase tracking-tighter">Acción Requerida</span>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-widest font-black">
                        <th class="px-8 py-5">Fecha Solicitud</th>
                        <th class="px-8 py-5">Agente</th>
                        <th class="px-8 py-5">Tipo</th>
                        <th class="px-8 py-5">Período</th>
                        <th class="px-8 py-5">Motivo</th>
                        <th class="px-8 py-5 text-right">Acciones de Aprobación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($solicitudes as $sol)
                        <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                            <td class="px-8 py-5 text-sm font-medium text-slate-500">{{ \Carbon\Carbon::parse($sol->fecha_solicitud)->format('d/m/Y') }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-50 text-indigo-700 rounded-full flex items-center justify-center font-bold mr-3 border border-indigo-100 shadow-sm group-hover:scale-110 transition-transform">
                                        {{ substr($sol->agente->age_nombre, 0, 1) }}{{ substr($sol->agente->age_apell1, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ optional($sol->agente)->age_apell1 }}, {{ optional($sol->agente)->age_nombre }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Legajo: {{ $sol->agente->age_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                                    {{ optional($sol->tipoLicencia)->nombre }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($sol->fecha_inicio)->format('d/m/Y') }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">al {{ \Carbon\Carbon::parse($sol->fecha_fin)->format('d/m/Y') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs text-slate-500 max-w-[200px] leading-relaxed italic" title="{{ $sol->motivo }}">
                                    "{{ Str::limit($sol->motivo, 50) }}"
                                </p>
                            </td>
                            <td class="px-8 py-5 text-right space-x-2 whitespace-nowrap">
                                <button wire:click="aprobarSolicitud({{ $sol->solicitud_id }})"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-emerald-200 transition-all text-xs flex items-center inline-flex group/btn">
                                    <i class="bi bi-check2-circle mr-2 text-lg transition-transform group-hover/btn:scale-125"></i> Aprobar
                                </button>
                                <button wire:click="rechazarSolicitud({{ $sol->solicitud_id }})"
                                    class="px-5 py-2.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold rounded-xl border border-rose-200 transition-all text-xs flex items-center inline-flex group/btn">
                                    <i class="bi bi-x-circle mr-2 text-lg transition-transform group-hover/btn:scale-125"></i> Rechazar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center animate__animated animate__pulse">
                                    <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6 shadow-inner">
                                        <i class="bi bi-check2-circle text-5xl"></i>
                                    </div>
                                    <h5 class="text-xl font-bold text-slate-800 mb-2">¡Todo al día!</h5>
                                    <p class="text-slate-500 font-medium">No hay solicitudes de licencias pendientes de revisión.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
