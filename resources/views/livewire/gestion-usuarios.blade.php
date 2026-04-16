<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-6">Gestión de Usuarios</h2>

    {{-- Filtros --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-8 shadow-sm">
        <div class="p-6">
            <h5 class="text-lg font-bold text-slate-700 mb-4 flex items-center"><i class="bi bi-funnel mr-2 text-indigo-600"></i> Filtros de Búsqueda</h5>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nombre / Apellido</label>
                    <input type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 bg-white" placeholder="Buscar..." wire:model="search">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Rol</label>
                    <select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700" wire:model="roleId">
                        <option value="">-- Todos los Roles --</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->rol_id }}">{{ $rol->rol_nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Departamento</label>
                    <select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700" wire:model="deptoId">
                        <option value="">-- Todos los Deptos --</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->tdep_id }}">{{ $dep->tdep_nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button wire:click="create" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm transition-all flex items-center">
                        <i class="bi bi-person-plus mr-2 text-lg"></i> Nuevo Agente
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Agente</th>
                        <th class="px-6 py-4 font-semibold">Legajo</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold">Depto</th>
                        <th class="px-6 py-4 font-semibold">Roles</th>
                        <th class="px-6 py-4 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($agentes as $agente)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-indigo-600 text-white rounded-full flex items-center justify-center w-10 h-10 font-bold mr-3 shadow-sm">
                                        {{ substr($agente->age_nombre, 0, 1) }}{{ substr($agente->age_apell1, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $agente->age_apell1 }}, {{ $agente->age_nombre }}</div>
                                        <div class="text-xs text-slate-400 font-medium">{{ $agente->age_numdoc }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ $agente->age_id }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($agente->age_activo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">Activo</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $agente->tdep_id ? 'bg-sky-100 text-sky-800 border border-sky-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $agente->departamento->tdep_nom ?? 'Sin Depto' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm flex flex-wrap gap-1">
                                @foreach($agente->roles as $rol)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-tighter">{{ $rol->rol_nombre }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2 whitespace-nowrap">
                                <button wire:click="edit({{ $agente->age_id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors inline-flex items-center" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $agente->age_id }})" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors inline-flex items-center" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        {{ $agentes->links() }}
    </div>

    <!-- Modal (Custom Premium Tailwind Modal) -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate__animated animate__fadeIn">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden animate__animated animate__zoomIn">
                <div class="bg-indigo-600 px-8 py-6 flex justify-between items-center">
                    <h5 class="text-xl font-bold text-white flex items-center">
                        <i class="bi {{ $age_id ? 'bi-pencil-square' : 'bi-person-plus-fill' }} mr-3"></i>
                        {{ $age_id ? 'Editar Agente' : 'Nuevo Agente' }}
                    </h5>
                    <button type="button" class="p-2 hover:bg-white/10 rounded-full transition-colors" wire:click="closeModal">
                        <i class="bi bi-x-lg text-white"></i>
                    </button>
                </div>
                <div class="p-8">
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                                <input type="text" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700" wire:model="age_nombre">
                                @error('age_nombre') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Apellido</label>
                                <input type="text" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700" wire:model="age_apell1">
                                @error('age_apell1') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">ID (Legajo)</label>
                                <input type="number" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-slate-50 text-slate-500 font-mono" wire:model="age_id" {{ $age_id ? 'readonly' : '' }}>
                                @error('age_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Documento</label>
                                <input type="text" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700" wire:model="age_numdoc">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Departamento</label>
                                <select class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700" wire:model="tdep_id">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($departamentos as $dep)
                                        <option value="{{ $dep->tdep_id }}">{{ $dep->tdep_nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Jefe Directo</label>
                                <select class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700" wire:model="jefe_age_id">
                                    <option value="">-- Sin Jefe --</option>
                                    @foreach($posibles_jefes as $jefe)
                                        <option value="{{ $jefe->age_id }}">{{ $jefe->age_apell1 }}, {{ $jefe->age_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Asignar Roles</label>
                            <select class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white text-slate-700 min-h-[120px]" multiple wire:model="selectedRoles">
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->rol_id }}">{{ $rol->rol_nombre }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider italic">Mantenga Ctrl presionado para seleccionar múltiples roles</p>
                        </div>
                    </form>
                </div>
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" class="px-6 py-2.5 text-slate-500 hover:text-slate-800 font-bold transition-all" wire:click="closeModal">Cancelar</button>
                    <button type="button" class="px-10 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-200 transition-all" wire:click="store">
                        {{ $age_id ? 'Guardar Cambios' : 'Crear Agente' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
