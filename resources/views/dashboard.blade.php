@extends('layouts.app')


@section('content')
    <div class="row mb-4 animate__animated animate__fadeIn">
        <div class="col-md-12">
            <h2 class="mb-3 text-primary" style="font-weight: 700;">Panel de Control</h2>
            <p class="text-muted">Bienvenido al Portal de Autogestión Hospitalaria.</p>
        </div>
    </div>


    <!-- Widgets de Resumen -->
    @livewire('dashboard-admin-actions')


    <!-- Accesos Rápidos (Bento Grid) -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="mb-4 fw-bold">Gestión y Servicios</h4>
        </div>
    </div>

    <div class="bento-grid">
        <!-- Mis Fichadas (Grande) -->
        <a href="{{ route('mis.fichadas') }}" class="bento-item bento-large bento-blue animate__animated animate__zoomIn">
            <div class="card-body">
                <i class="bi bi-clock"></i>
                <h4 class="fw-bold">Mis Fichadas</h4>
                <p class="small text-center opacity-75">Control de ingresos, egresos y horas trabajadas.</p>
            </div>
        </a>

        <!-- Solicitar Licencia (Ancho) -->
        <a href="{{ route('mis.licencias') }}" class="bento-item bento-wide bento-green animate__animated animate__zoomIn" style="animation-delay: 0.1s;">
            <div class="card-body">
                <i class="bi bi-journal-medical"></i>
                <h4 class="fw-bold">Solicitar Licencia</h4>
                <p class="small text-center opacity-75">Gestione sus vacaciones y permisos médicos.</p>
            </div>
        </a>

        <!-- Fichadas Jefe (Solo Jefes/RRHH/Admin) -->
        @if(Auth::user()->hasRole(['ADMIN', 'RRHH', 'JEFE']))
            <a href="{{ route('fichadas.jefe') }}" class="bento-item bento-cyan animate__animated animate__zoomIn" style="animation-delay: 0.15s;">
                <div class="card-body">
                    <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold">Fichadas Jefe</h5>
                </div>
            </a>
        @endif

        <!-- Gestión de Personal (Solo Admin/RRHH) -->
        @if(Auth::user()->hasRole(['ADMIN', 'RRHH']))
            <a href="{{ route('gestion.usuarios') }}" class="bento-item bento-orange animate__animated animate__zoomIn" style="animation-delay: 0.2s;">
                <div class="card-body">
                    <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold">Personal</h5>
                </div>
            </a>

            @if($moduloReportes ?? true)
                <a href="{{ route('reportes') }}" class="bento-item bento-cyan animate__animated animate__zoomIn" style="animation-delay: 0.3s;">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-bar-graph" style="font-size: 2rem;"></i>
                        <h5 class="fw-bold">Reportes</h5>
                    </div>
                </a>
            @endif
        @endif

        <!-- Gestión de Novedades -->
        @if(Auth::user()->hasRole(['ADMIN', 'GESTOR_NOVEDADES']) && ($moduloNovedades ?? true))
            <a href="{{ route('gestion.novedades') }}" class="bento-item bento-purple animate__animated animate__zoomIn" style="animation-delay: 0.4s;">
                <div class="card-body">
                    <i class="bi bi-megaphone-fill" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold">Novedades</h5>
                </div>
            </a>
        @endif

        <!-- Configuración -->
        @if(Auth::user()->isAdmin())
            <a href="{{ route('configuracion') }}" class="bento-item bento-dark animate__animated animate__zoomIn" style="animation-delay: 0.5s;">
                <div class="card-body">
                    <i class="bi bi-gear" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold">Ajustes</h5>
                </div>
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


