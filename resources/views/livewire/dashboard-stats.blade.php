<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
        <div class="p-6 flex items-center">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mr-4 shadow-sm">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $licenciasActivas }}</h3>
                <p class="text-slate-500 text-sm font-medium">Licencias Hoy</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
        <div class="p-6 flex items-center">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mr-4 shadow-sm">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $fichadasHoy }}</h3>
                <p class="text-slate-500 text-sm font-medium">Presentes</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
        <div class="p-6 flex items-center">
            <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl mr-4 shadow-sm">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $usuariosActivos }}</h3>
                <p class="text-slate-500 text-sm font-medium">Total Personal</p>
            </div>
        </div>
    </div>
</div>
