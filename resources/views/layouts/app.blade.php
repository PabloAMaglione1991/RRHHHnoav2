<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Hospital') }}</title>

    <!-- Tailwind Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap (Legacy Components) & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

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
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
            :style="sidebarOpen ? 'width: 280px;' : 'width: 100px;'"
            class="fixed inset-y-0 left-0 z-50 flex flex-col bg-[#1e1e24] text-white transition-all duration-300 ease-in-out shadow-[8px_0_30px_rgba(0,0,0,0.1)] border-r border-[#2a2a32]">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center transition-all duration-300" :class="sidebarOpen ? 'h-28 px-6' : 'h-28 px-2'">
                <a href="{{ route('dashboard') }}" class="group flex items-center justify-center w-full transition-all duration-300 no-underline">
                    <span x-show="sidebarOpen" class="font-extrabold text-[1.35rem] tracking-tight flex items-center justify-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-400 to-indigo-500 shadow-lg shadow-indigo-500/20 flex items-center justify-center">
                            <i class="bi bi-hospital text-white text-xl"></i>
                        </div>
                        <span class="text-white hover:text-indigo-400 transition-colors">Portal Hospital</span>
                    </span>
                    <div x-show="!sidebarOpen" class="flex font-extrabold text-2xl items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-400 to-indigo-500 shadow-lg shadow-indigo-500/20 text-white">
                        P
                    </div>
                </a>
            </div>

            <!-- Sidebar Links -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-3 relative custom-scrollbar transition-all duration-300" :class="sidebarOpen ? 'px-4' : 'px-2'">
                <a href="{{ route('dashboard') }}" 
                    class="flex items-center rounded-[1rem] transition-all duration-300 no-underline group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-[#8c8c9a] hover:text-white hover:bg-white/5' }}"
                    :class="sidebarOpen ? 'px-4 py-3.5 mx-2' : 'px-0 py-4 justify-center mx-1'">
                    <i class="bi bi-grid text-[1.5rem] flex-shrink-0 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-white' : '' }}"></i>
                    <span x-show="sidebarOpen" class="ml-3 font-semibold text-[0.95rem] tracking-wide">Dashboard</span>
                </a>

                @isset($sidebarItems)
                    @foreach($sidebarItems as $item)
                        @if($item['route'] !== 'dashboard')
                            <a href="{{ $item['url'] }}" 
                                class="flex items-center rounded-[1rem] transition-all duration-300 no-underline group {{ $item['active'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-[#8c8c9a] hover:text-white hover:bg-white/5' }}"
                                :class="sidebarOpen ? 'px-4 py-3.5 mx-2' : 'px-0 py-4 justify-center mx-1'">
                                <i class="{{ $item['icon'] }} text-[1.5rem] flex-shrink-0 text-center transition-transform group-hover:scale-110 {{ $item['active'] ? 'text-white' : '' }}"></i>
                                <span x-show="sidebarOpen" class="ml-3 font-semibold text-[0.95rem] tracking-wide truncate">{{ $item['nombre'] }}</span>
                            </a>
                        @endif
                    @endforeach
                @endisset
            </nav>
            
            <!-- User Section Minimal -->
            @auth
            <div class="border-t border-[#2a2a32] pt-6 mb-6 mt-auto transition-all duration-300" :class="sidebarOpen ? 'px-8' : 'px-0 flex justify-center'">
                <div class="flex items-center transition-all duration-300" :class="sidebarOpen ? 'gap-3' : 'gap-0'">
                    <div class="w-12 h-12 rounded-2xl bg-slate-700 flex-shrink-0 border-2 border-indigo-500/50 overflow-hidden shadow-lg">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4F46E5&color=fff" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-col overflow-hidden transition-all duration-300" x-show="sidebarOpen">
                        <div class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[0.7rem] text-[#8c8c9a] font-medium tracking-wide uppercase">Agente Activo</div>
                    </div>
                </div>
            </div>
            @endauth
        </aside>

        <!-- Main Content -->
        <main 
            :style="sidebarOpen ? 'margin-left: 280px;' : 'margin-left: 100px;'"
            class="flex-1 transition-all duration-300 ease-in-out min-h-screen border-l border-slate-200/50">
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
                        <div class="flex flex-col items-end mr-2 text-right">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-400 leading-none mb-1">Usuario Activo</span>
                            <span class="text-sm font-bold text-slate-700 leading-none">{{ Auth::user()->nombre_completo }}</span>
                        </div>
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
