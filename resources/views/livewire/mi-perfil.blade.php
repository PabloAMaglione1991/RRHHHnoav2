<div>
    <h2 class="text-3xl font-bold text-slate-800 mb-6">Mi Perfil</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tarjeta de Información Personal -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden text-center p-8">
                <div class="bg-indigo-600 text-white rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4 text-3xl font-bold shadow-md">
                    {{ substr($agente->age_nombre, 0, 1) }}{{ substr($agente->age_apell1, 0, 1) }}
                </div>
                <h4 class="text-2xl font-bold text-slate-800">{{ $agente->age_nombre }} {{ $agente->age_apell1 }}</h4>
                <p class="text-slate-500 mb-2">{{ $agente->departamento->tdep_nom ?? 'Sin departamento' }}</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Activo
                </span>

                <div class="my-6 border-t border-slate-100"></div>

                <div class="text-left space-y-3">
                    <div class="flex items-center text-slate-700">
                        <i class="bi bi-person-badge text-indigo-600 mr-3 text-lg"></i>
                        <span class="text-sm font-medium">Legajo:</span>
                        <span class="ml-auto text-sm text-slate-600 font-bold group-hover:text-indigo-600">{{ $agente->age_id }}</span>
                    </div>
                    <div class="flex items-center text-slate-700">
                        <i class="bi bi-card-heading text-indigo-600 mr-3 text-lg"></i>
                        <span class="text-sm font-medium">Documento:</span>
                        <span class="ml-auto text-sm text-slate-600 font-bold">{{ $agente->age_numdoc }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Seguridad -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center">
                    <i class="bi bi-shield-lock text-indigo-600 mr-3 text-xl"></i>
                    <h5 class="text-slate-800 font-semibold text-lg">Seguridad de la Cuenta</h5>
                </div>
                <div class="p-8">
                    <div class="mb-8">
                        <h5 class="text-lg font-bold text-slate-800 mb-1">Cambiar Contraseña</h5>
                        <p class="text-slate-500 text-sm">Asegúrese de usar una contraseña segura que no utilice en otros sitios.</p>
                    </div>

                    @if (session()->has('message'))
                        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg flex items-center mb-6 border border-emerald-200" role="alert">
                            <i class="bi bi-check-circle-fill text-xl mr-3"></i>
                            <span class="flex-1 font-medium">{{ session('message') }}</span>
                            <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('message', null)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword" class="max-w-md">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nueva Contraseña</label>
                            <input type="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" wire:model="password">
                            @error('password') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Contraseña</label>
                            <input type="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700" wire:model="password_confirmation">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="bi bi-save mr-2"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
