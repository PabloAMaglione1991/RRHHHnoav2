@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-800 mb-2">Panel de Control</h2>
        <p class="text-slate-500">Bienvenido al Portal de Autogestión Hospitalaria.</p>
    </div>

    <!-- Widgets de Resumen -->
    @livewire('dashboard-admin-actions')

    <!-- Accesos Rápidos (Bento Grid) -->
    <div class="mb-4">
        <h4 class="text-xl font-bold text-slate-700">Gestión y Servicios</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Mis Fichadas (Grande) -->
        <a href="{{ route('mis.fichadas') }}" class="col-span-1 md:col-span-2 row-span-2 group flex flex-col items-center justify-center p-8 bg-white border-l-4 border-blue-500 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-slate-700 hover:text-blue-600">
            <i class="bi bi-clock text-5xl mb-4 text-blue-500 group-hover:scale-110 transition-transform"></i>
            <h4 class="text-2xl font-bold mb-2">Mis Fichadas</h4>
            <p class="text-sm text-center text-slate-500">Control de ingresos, egresos y horas trabajadas.</p>
        </a>

        <!-- Solicitar Licencia (Ancho) -->
        <a href="{{ route('mis.licencias') }}" class="col-span-1 md:col-span-2 group flex flex-col items-center justify-center p-6 bg-white border-l-4 border-emerald-500 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-slate-700 hover:text-emerald-600">
            <i class="bi bi-journal-medical text-4xl mb-3 text-emerald-500 group-hover:scale-110 transition-transform"></i>
            <h4 class="text-xl font-bold mb-1">Solicitar Licencia</h4>
            <p class="text-xs text-center text-slate-500">Gestione sus vacaciones y permisos médicos.</p>
        </a>

        <!-- Fichadas Jefe (Solo Jefes/RRHH/Admin) -->
        @if(Auth::user()->hasRole(['ADMIN', 'RRHH', 'JEFE']))
            <a href="{{ route('fichadas.jefe') }}" class="group flex flex-col items-center justify-center p-6 bg-white border-l-4 border-cyan-500 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-slate-700 hover:text-cyan-600">
                <i class="bi bi-people-fill text-4xl mb-2 text-cyan-500 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-lg">Fichadas Jefe</h5>
            </a>
        @endif

        <!-- Gestión de Personal (Solo Admin/RRHH) -->
        @if(Auth::user()->hasRole(['ADMIN', 'RRHH']))
            <a href="{{ route('gestion.usuarios') }}" class="group flex flex-col items-center justify-center p-6 bg-white border-l-4 border-orange-500 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-slate-700 hover:text-orange-600">
                <i class="bi bi-people-fill text-4xl mb-2 text-orange-500 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-lg">Personal</h5>
            </a>

            @if($moduloReportes ?? true)
                <a href="{{ route('reportes') }}" class="group flex flex-col items-center justify-center p-6 bg-white border-l-4 border-cyan-500 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-slate-700 hover:text-cyan-600">
                    <i class="bi bi-file-earmark-bar-graph text-4xl mb-2 text-cyan-500 group-hover:scale-110 transition-transform"></i>
                    <h5 class="font-bold text-lg">Reportes</h5>
                </a>
            @endif
        @endif

        <!-- Gestión de Novedades -->
        @if(Auth::user()->hasRole(['ADMIN', 'GESTOR_NOVEDADES']) && ($moduloNovedades ?? true))
            <a href="{{ route('gestion.novedades') }}" class="group flex flex-col items-center justify-center p-6 bg-white border-l-4 border-purple-500 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-slate-700 hover:text-purple-600">
                <i class="bi bi-megaphone-fill text-4xl mb-2 text-purple-500 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-lg">Novedades</h5>
            </a>
        @endif

        <!-- Configuración -->
        @if(Auth::user()->isAdmin())
            <a href="{{ route('configuracion') }}" class="group flex flex-col items-center justify-center p-6 bg-white border-l-4 border-slate-700 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-slate-700 hover:text-slate-900">
                <i class="bi bi-gear text-4xl mb-2 text-slate-700 group-hover:scale-110 transition-transform"></i>
                <h5 class="font-bold text-lg">Ajustes</h5>
            </a>
        @endif
    </div>

    <!-- Widgets de Estadisticas -->
    @livewire('dashboard-stats')

    <!-- Analíticas e Insights (Solo Jefes/RRHH/Admin) -->
    @if(Auth::user()->hasRole(['ADMIN', 'RRHH', 'JEFE']))
        @livewire('dashboard-charts')
    @endif

    <!-- Novedades -->
    @if($moduloNovedades ?? true)
        @livewire('novedades')
    @endif
@endsection
