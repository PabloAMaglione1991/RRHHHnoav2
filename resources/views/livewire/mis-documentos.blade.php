<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-6">Mis Documentos</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario de Subida -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h5 class="text-white font-semibold text-lg flex items-center"><i class="bi bi-cloud-upload mr-2"></i> Subir Documento</h5>
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

                    <form wire:submit.prevent="subirDocumento">
                        @if($esAdmin)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Agente</label>
                                <select wire:model="agenteSeleccionado" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($agentes as $ag)
                                        <option value="{{ $ag->age_id }}">{{ $ag->age_apell1 }}, {{ $ag->age_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Documento</label>
                            <select wire:model="tipo_documento" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700">
                                <option value="">-- Seleccionar --</option>
                                <option value="DNI">DNI</option>
                                <option value="Certificado Médico">Certificado Médico</option>
                                <option value="Título">Título</option>
                                <option value="Certificado Vacunación">Certificado Vacunación</option>
                                <option value="Otro">Otro</option>
                            </select>
                            @error('tipo_documento') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Archivo</label>
                            <input type="file" wire:model="archivo" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('archivo') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="archivo" class="text-indigo-600 text-xs mt-2 flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Cargando archivo...
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones (Opcional)</label>
                            <textarea wire:model="observaciones" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" rows="2"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" wire:loading.attr="disabled" wire:target="archivo">
                            <i class="bi bi-upload mr-2"></i> Subir Documento
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de Documentos -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h5 class="text-slate-800 font-semibold text-lg flex items-center"><i class="bi bi-files mr-2 text-indigo-600"></i> Documentos Guardados</h5>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Tipo</th>
                                <th class="px-6 py-4 font-semibold">Archivo</th>
                                <th class="px-6 py-4 font-semibold">Fecha</th>
                                <th class="px-6 py-4 font-semibold">Subido Por</th>
                                <th class="px-6 py-4 font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($documentos as $doc)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase">
                                            {{ $doc->tipo_documento }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 truncate max-w-[150px]" title="{{ $doc->nombre_archivo }}">{{ $doc->nombre_archivo }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($doc->fecha_subida)->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ optional($doc->subidoPor)->nombre_completo }}</td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <button wire:click="descargarDocumento({{ $doc->doc_id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors inline-flex items-center" title="Descargar">
                                            <i class="bi bi-download"></i>
                                        </button>
                                        @if($esAdmin)
                                            <button wire:click="eliminarDocumento({{ $doc->doc_id }})" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors inline-flex items-center" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este documento?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-inbox text-4xl text-slate-300 mb-2"></i>
                                            <p>No hay documentos guardados.</p>
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
