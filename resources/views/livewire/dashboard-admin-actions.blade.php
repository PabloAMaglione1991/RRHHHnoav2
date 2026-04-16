<div>
    @if($isAdmin)
        <div class="bg-white rounded-2xl shadow-lg border border-amber-200 overflow-hidden mb-8 group hover:shadow-xl transition-all duration-300">
            <div class="bg-amber-400 px-6 py-4 flex items-center">
                <i class="bi bi-shield-lock-fill text-slate-800 mr-2 text-xl"></i>
                <h5 class="text-slate-800 font-bold text-lg">Acciones de Administrador</h5>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex-1">
                        <h5 class="text-lg font-bold text-slate-800 mb-1">Sincronización de Fichadas</h5>
                        <p class="text-slate-500 text-sm">Importar manualmente los últimos registros del reloj de fichadas del sistema legacy.</p>
                    </div>

                    <button wire:click="sincronizar" class="px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-slate-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="sincronizar" class="flex items-center">
                            <i class="bi bi-arrow-repeat mr-2 text-lg"></i> Sincronizar Ahora
                        </span>
                        <span wire:loading wire:target="sincronizar" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Sincronizando...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center mb-8 border border-emerald-200 animate__animated animate__fadeIn">
                <i class="bi bi-check-circle-fill text-xl mr-3"></i>
                <span class="flex-1 font-medium">{{ session('message') }}</span>
                <button type="button" class="text-emerald-500 hover:text-emerald-700 focus:outline-none" wire:click="$set('message', null)">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
    @endif
</div>
