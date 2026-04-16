<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-6">Mis Licencias</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h5 class="text-white font-semibold text-lg flex items-center"><i class="bi bi-plus-circle mr-2"></i> Nueva Solicitud</h5>
                </div>
                <div class="p-6">
                    @if (session()->has('message'))
                        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg flex items-center mb-4 border border-emerald-200" role="alert">
                            <span class="flex-1 font-medium text-sm">{{ session('message') }}</span>
                            <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('message', null)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="bg-rose-50 text-rose-700 p-4 rounded-lg flex items-center mb-4 border border-rose-200" role="alert">
                            <span class="flex-1 font-medium text-sm">{{ session('error') }}</span>
                            <button type="button" class="text-rose-500 hover:text-rose-700 focus:outline-none" wire:click="$set('error', null)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    <form wire:submit.prevent="store">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Licencia</label>
                            {{-- IMPORTANTE: Cambié lt_id por licencia_tipo_id para que coincida con tu base de datos --}}
                            <select wire:model="licencia_tipo_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700">
                                <option value="">-- Seleccionar --</option>
                                @foreach($tipos_licencia as $tipo)
                                    {{-- CORRECCIÓN: Usamos $tipo->id porque lt_id no existe en esa tabla --}}
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('licencia_tipo_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio</label>
                            <input type="date" wire:model="fecha_inicio" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700">
                            @error('fecha_inicio') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin</label>
                            <input type="date" wire:model="fecha_fin" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700">
                            @error('fecha_fin') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Motivo (Opcional)</label>
                            <textarea wire:model="motivo" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" rows="3"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Enviar Solicitud
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h5 class="text-slate-800 font-semibold text-lg flex items-center"><i class="bi bi-clock-history mr-2 text-indigo-600"></i> Historial de Solicitudes</h5>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Fecha Solicitud</th>
                                <th class="px-6 py-4 font-semibold">Tipo</th>
                                <th class="px-6 py-4 font-semibold">Período</th>
                                <th class="px-6 py-4 font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($misSolicitudes as $sol)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ \Carbon\Carbon::parse($sol->fecha_solicitud)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ optional($sol->tipoLicencia)->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($sol->fecha_inicio)->format('d/m/Y') }} <span class="text-slate-400 mx-1">al</span>
                                        {{ \Carbon\Carbon::parse($sol->fecha_fin)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $clase = match ($sol->estado) {
                                                'PENDIENTE' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'APROBADA' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'RECHAZADA' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                default => 'bg-slate-100 text-slate-800 border-slate-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $clase }}">
                                            {{ $sol->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-inboxes text-4xl text-slate-300 mb-2"></i>
                                            <p>No tienes solicitudes registradas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
