<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Hospital') }}</title>

    <!-- Tailwind Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js (for interactivity/sidebar toggle) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- App Styles (Tailwind Compiled) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 overflow-x-hidden" x-data="{ sidebarOpen: true }">
    <div class="flex min-h-screen bg-slate-50 transition-colors duration-300">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0 lg:w-20 lg:translate-x-0'" class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 text-white transition-all duration-300 ease-in-out shadow-xl">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-20 border-b border-slate-800">
                <a href="{{ route('dashboard') }}" class="group flex items-center justify-center p-2 mt-4 mb-4 mx-4 w-full bg-white/5 backdrop-blur-md rounded-xl border border-white/10 hover:bg-white/10 hover:border-indigo-500 hover:-translate-y-0.5 transition-all duration-300">
                    <span x-show="sidebarOpen" class="font-bold text-sm tracking-wide">Portal Hospital</span>
                    <span x-show="!sidebarOpen" class="hidden lg:block font-bold text-xl">PH</span>
                </a>
            </div>

            <!-- Sidebar Links -->
            <nav class="flex-1 overflow-y-auto py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-600/[0.15] text-indigo-400 font-semibold border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi bi-house-door text-xl flex-shrink-0 w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="ml-3">Inicio</span>
                </a>

                @isset($sidebarItems)
                    @foreach($sidebarItems as $item)
                        @if($item['route'] !== 'dashboard')
                            <a href="{{ $item['url'] }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ $item['active'] ? 'bg-indigo-600/[0.15] text-indigo-400 font-semibold border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                <i class="{{ $item['icon'] }} text-xl flex-shrink-0 w-6 text-center"></i>
                                <span x-show="sidebarOpen" class="ml-3 truncate">{{ $item['nombre'] }}</span>
                            </a>
                        @endif
                    @endforeach
                @endisset
            </nav>
        </aside>

        <!-- Main Content -->
        <main :class="sidebarOpen ? 'ml-64' : 'ml-0 lg:ml-20'" class="flex-1 transition-all duration-300 ease-in-out min-h-screen">
            <!-- Navbar -->
            <header class="sticky top-0 z-40 flex items-center justify-between h-20 px-6 bg-white/80 backdrop-blur-md border-b border-slate-200">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h4 class="text-xl font-bold text-indigo-600 hidden sm:block">Panel de Control</h4>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <span class="font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-600 border border-red-200 rounded-full hover:bg-red-50 hover:border-red-300 transition-all focus:outline-none focus:ring-2 focus:ring-red-500">Salir</button>
                        </form>
                    @endauth
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 md:p-8 max-w-7xl mx-auto">
                <!-- Flash Messages -->
                @if(session()->has('success'))
                    <div class="flex items-center p-4 mb-6 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm animate-fade-in-down" x-data="{ show: true }" x-show="show">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="flex-1 font-medium">{{ session('success') }}</span>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="flex items-center p-4 mb-6 rounded-xl bg-red-50 text-red-700 border border-red-200 shadow-sm animate-fade-in-down" x-data="{ show: true }" x-show="show">
                        <span class="flex-1 font-medium">{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif
                @if(session()->has('message'))
                    <div class="flex items-center p-4 mb-6 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 shadow-sm animate-fade-in-down" x-data="{ show: true }" x-show="show">
                        <span class="flex-1 font-medium">{{ session('message') }}</span>
                        <button @click="show = false" class="text-blue-500 hover:text-blue-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
