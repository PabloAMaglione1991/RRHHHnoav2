<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Hospital') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('css/hospital-theme.css') }}">
    
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-center">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                    <div class="portal-title-btn">
                        <span>Portal Hospital</span>
                    </div>
                </a>
            </div>
            <div class="list-group list-group-flush mt-3">
                <!-- Ítem Dashboard (Siempre Visible) -->
                <a href="{{ route('dashboard') }}" class="list-group-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> <span>Inicio</span>
                </a>

                <!-- Ítems Dinámicos según Permisos -->
                @isset($sidebarItems)
                    @foreach($sidebarItems as $item)
                        @if($item['route'] !== 'dashboard') {{-- Evitamos duplicar Inicio --}}
                            <a href="{{ $item['url'] }}" class="list-group-item {{ $item['active'] ? 'active' : '' }}">
                                <i class="{{ $item['icon'] }}"></i> <span>{{ $item['nombre'] }}</span>
                            </a>
                        @endif
                    @endforeach
                @endisset
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar-custom">
                <button id="menu-toggle"><i class="bi bi-list"></i></button>
                <h4 class="mb-0 text-primary fw-bold">Panel de Control</h4>
                <div class="ms-auto d-flex align-items-center gap-3">
                    @auth
                        <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Salir</button>
                        </form>
                    @endauth
                </div>
            </nav>

            <div class="container-fluid">
                <!-- Mensajes Globales -->
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i> {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
        });
    </script>
</body>
</html>
