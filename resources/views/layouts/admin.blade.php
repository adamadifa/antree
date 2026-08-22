<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $institution->app_name ?? config('app.name', 'Antree') }} Admin - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; background-color: #F4F6F9; }
        .bg-sidebar { background-color: #FFFFFF; }
        .active-menu { background-color: #FFF5F2; color: #FD397A !important; border-left: 3px solid #FD397A; }
        .active-menu svg { color: #FD397A !important; }
        .card-shadow { box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.08); }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    </style>
</head>
<body class="bg-[#F8F9FA] antialiased text-slate-800 text-sm">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200/80 flex-shrink-0 flex flex-col hidden lg:flex select-none z-30">
            {{-- Brand Logo --}}
            <div class="h-16 px-6 flex items-center justify-between border-b border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
                    @if(isset($institution) && $institution->logo_path)
                        <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-8 h-8 object-contain rounded-lg">
                    @else
                        <div class="w-8 h-8 bg-gradient-to-tr from-orange-500 to-rose-500 rounded-lg flex items-center justify-center shadow-sm shadow-orange-500/20 text-white font-black text-base">
                            A
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="text-base font-extrabold tracking-tight text-slate-800 flex items-center gap-1">
                            {{ $institution->app_name ?? 'SmartHR' }}
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        </span>
                    </div>
                </a>
                <button class="text-slate-400 hover:text-slate-600 p-1 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </button>
            </div>
            
            {{-- Navigation Items --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                <p class="px-3 pt-2 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Main Menu</p>
                
                {{-- Dashboard Single Link --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-rose-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Queue Management</p>

                <a href="{{ route('admin.service-types.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium transition duration-150 {{ request()->routeIs('admin.service-types.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.service-types.*') ? 'text-rose-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Service Types</span>
                </a>
                
                <a href="{{ route('admin.counters.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium transition duration-150 {{ request()->routeIs('admin.counters.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.counters.*') ? 'text-rose-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Counters</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium transition duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-rose-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Users & Staff</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium transition duration-150 {{ request()->routeIs('admin.reports.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.reports.*') ? 'text-rose-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Reports & Analytics</span>
                </a>

                <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">System Settings</p>

                <a href="{{ route('admin.display-settings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium transition duration-150 {{ request()->routeIs('admin.display-settings.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.display-settings.*') ? 'text-rose-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Display Screen</span>
                </a>

                <a href="{{ route('admin.general-settings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium transition duration-150 {{ request()->routeIs('admin.general-settings.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.general-settings.*') ? 'text-rose-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>General Settings</span>
                </a>

                <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Public View</p>

                <a href="{{ route('display.index') }}" target="_blank" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition duration-150">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span>Live Display</span>
                </a>
                
                <a href="{{ route('kiosk.index') }}" target="_blank" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition duration-150">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Kiosk Ambil Tiket</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden bg-[#F8F9FA]">
            <!-- Top Navbar (SmartHR Header) -->
            <header class="h-16 px-6 bg-white border-b border-slate-200/80 flex items-center justify-between z-20">
                {{-- Search Bar --}}
                <div class="flex items-center space-x-3 flex-1 max-w-md">
                    <button class="lg:hidden text-slate-500 hover:text-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" placeholder="Search in HRMS" class="block w-full pl-9 pr-14 py-1.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:bg-white transition text-xs font-medium text-slate-700">
                        <span class="absolute inset-y-0 right-0 pr-2 flex items-center">
                            <kbd class="text-[10px] bg-slate-200/70 text-slate-500 px-1.5 py-0.5 rounded font-mono border border-slate-300">CTRL + /</kbd>
                        </span>
                    </div>
                </div>
                
                {{-- Right Navigation Tools --}}
                <div class="flex items-center space-x-3 sm:space-x-4">
                    {{-- Grid icon --}}
                    <button class="w-8 h-8 rounded-full text-slate-500 hover:bg-slate-100 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>

                    {{-- Fullscreen/Scan icon --}}
                    <button onclick="document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen()" class="w-8 h-8 rounded-full text-slate-500 hover:bg-slate-100 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    </button>

                    {{-- Notification Bell --}}
                    <div class="relative">
                        <button class="w-8 h-8 rounded-full text-slate-500 hover:bg-slate-100 flex items-center justify-center relative">
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </button>
                    </div>

                    {{-- Chat / Message Icon --}}
                    <button class="w-8 h-8 rounded-full text-slate-500 hover:bg-slate-100 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </button>

                    {{-- Email Icon --}}
                    <button class="w-8 h-8 rounded-full text-slate-500 hover:bg-slate-100 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </button>

                    {{-- User Profile Avatar Dropdown --}}
                    <div class="flex items-center space-x-2 pl-3 border-l border-slate-200">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
                        <div class="relative group cursor-pointer">
                            <div class="flex items-center space-x-2">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="w-8 h-8 rounded-full object-cover ring-2 ring-rose-400">
                            </div>
                            {{-- Dropdown menu on hover/click --}}
                            <div class="hidden group-hover:block absolute right-0 top-full pt-2 w-48 z-50">
                                <div class="bg-white border border-slate-100 rounded-xl shadow-lg py-1.5">
                                    <div class="px-4 py-2 border-b border-slate-100">
                                        <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">{{ ucfirst(Auth::user()->role) }}</p>
                                    </div>
                                    <a href="{{ route('admin.general-settings.index') }}" class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50">Settings</a>
                                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium">Logout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- Scrollable Container --}}
            <div class="flex-1 overflow-y-auto px-6 py-6 custom-scrollbar">
                @yield('content')
                
                <footer class="mt-10 pt-6 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-2">
                    <p>2014-2025 &copy; {{ $institution->footer_text ?? 'SmartHR - Antree System' }}</p>
                    <p>Designed & Developed By <span class="text-rose-500 font-semibold">Dreams</span></p>
                </footer>
            </div>
        </main>
    </div>

    {{-- Flash Message Handler --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            iconColor: '#059669',
            title: '<span class="text-emerald-800">Success!</span>',
            html: '<span class="text-emerald-700 font-medium">{{ session('success') }}</span>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            padding: '1.25rem',
            background: '#ECFDF5',
            customClass: {
                popup: 'card-shadow border border-emerald-100',
                timerProgressBar: 'bg-emerald-400'
            }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            iconColor: '#DC2626',
            title: '<span class="text-red-800">Oops...</span>',
            html: '<span class="text-red-700 font-medium">{{ session('error') }}</span>',
            confirmButtonColor: '#991B1B',
            confirmButtonText: 'Got it',
            padding: '2rem',
            background: '#FEF2F2',
            customClass: {
                popup: 'card-shadow border border-red-100',
                confirmButton: 'px-8 py-3 rounded-xl font-bold text-sm'
            }
        });
    </script>
    @endif
    @stack('scripts')
</body>
</html>
