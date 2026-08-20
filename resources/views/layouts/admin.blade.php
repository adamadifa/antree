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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-sidebar { background-color: #F8F9FA; }
        .active-menu { background-color: #2DD4BF; color: white !important; }
        .active-menu svg { color: white !important; }
        .card-shadow { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02); }
        .avatar-img { width: 48px !important; height: 48px !important; object-fit: cover; }
        
        /* SweetAlert Customization */
        .swal2-popup { border-radius: 1.5rem !important; border: 1px solid #F1F5F9 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .swal2-title { font-weight: 800 !important; color: #1E293B !important; }
        .swal2-confirm { background-color: #EF4444 !important; border-radius: 0.75rem !important; font-weight: 700 !important; padding: 0.75rem 1.5rem !important; }
        .swal2-cancel { background-color: #F8F9FA !important; color: #64748B !important; border-radius: 0.75rem !important; font-weight: 700 !important; padding: 0.75rem 1.5rem !important; }
    </style>
</head>
<body class="bg-white antialiased text-slate-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-72 bg-sidebar border-r border-slate-100 flex-shrink-0 flex flex-col hidden md:flex">
            <div class="p-8 flex items-center space-x-3 mb-4">
                @if(isset($institution) && $institution->logo_path)
                    <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-10 h-10 object-contain rounded-xl">
                @else
                    <div class="w-10 h-10 bg-[#2DD4BF] rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                @endif
                <span class="text-2xl font-extrabold tracking-tight text-slate-800">{{ $institution->app_name ?? 'Antree' }}</span>
            </div>
            
            <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-4 rounded-2xl transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'active-menu shadow-lg shadow-teal-100' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="font-bold">Dashboard</span>
                </a>
                
                <p class="pt-6 pb-2 px-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.1em]">Management</p>

                <a href="{{ route('admin.service-types.index') }}" class="flex items-center space-x-3 p-4 rounded-2xl transition duration-200 {{ request()->routeIs('admin.service-types.*') ? 'active-menu shadow-lg shadow-teal-100' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.service-types.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="font-bold">Service Types</span>
                </a>
                
                <a href="{{ route('admin.counters.index') }}" class="flex items-center space-x-3 p-4 rounded-2xl transition duration-200 {{ request()->routeIs('admin.counters.*') ? 'active-menu shadow-lg shadow-teal-100' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.counters.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="font-bold">Counters</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 p-4 rounded-2xl transition duration-200 {{ request()->routeIs('admin.users.*') ? 'active-menu shadow-lg shadow-teal-100' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="font-bold">Users</span>
                </a>

                <a href="{{ route('admin.display-settings.index') }}" class="flex items-center space-x-3 p-4 rounded-2xl transition duration-200 {{ request()->routeIs('admin.display-settings.*') ? 'active-menu shadow-lg shadow-teal-100' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.display-settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="font-bold">Display Settings</span>
                </a>

                <a href="{{ route('admin.general-settings.index') }}" class="flex items-center space-x-3 p-4 rounded-2xl transition duration-200 {{ request()->routeIs('admin.general-settings.*') ? 'active-menu shadow-lg shadow-teal-100' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.general-settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-bold">General Settings</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 p-4 rounded-2xl transition duration-200 {{ request()->routeIs('admin.reports.*') ? 'active-menu shadow-lg shadow-teal-100' : 'text-slate-500 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="font-bold">Laporan</span>
                </a>

                <p class="pt-6 pb-2 px-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.1em]">Personal</p>
                
                <a href="#" class="flex items-center space-x-3 p-4 rounded-2xl text-slate-500 hover:bg-slate-100 transition duration-200">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="font-bold">Inbox</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden bg-[#F8F9FA]">
            <!-- Header -->
            <header class="h-24 px-10 flex items-center justify-between ">
                <div class="flex-1 max-w-xl">
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-[#2DD4BF] transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" placeholder="Search anything" class="block w-full pl-12 pr-4 py-3 bg-white border-transparent rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-100 focus:bg-white transition duration-200 text-sm font-medium text-slate-600 shadow-sm border border-slate-100">
                    </div>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- Icons -->
                    <div class="flex space-x-2">
                        <button class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                        <button class="w-12 h-12 rounded-2xl bg-[#E0F2F1]/50 border border-teal-100 flex items-center justify-center text-[#2DD4BF] transition shadow-sm relative">
                            <span class="absolute top-3 right-3 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </button>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
                        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-red-500 transition shadow-sm group" title="Logout">
                            <svg class="w-5 h-5 group-hover:scale-110 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </div>
                    
                    <!-- User -->
                    <div class="flex items-center space-x-4 pl-6 border-l border-slate-200">
                        <div class="text-right">
                            <p class="text-sm font-extrabold text-slate-800 leading-none mb-1">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ ucfirst(Auth::user()->role) }} User</p>
                        </div>
                        <img src="{{ asset('images/admin_avatar.png') }}" alt="Avatar" class="avatar-img w-12 h-12 rounded-2xl border-2 border-white shadow-sm ring-4 ring-teal-50">
                    </div>
                </div>
            </header>
            
            <div class="flex-1 overflow-y-auto px-10 pb-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">@yield('header')</h2>
                        <nav class="flex text-xs font-bold space-x-2 mt-1">
                            <span class="text-slate-400">Dashboard</span>
                            <span class="text-[#2DD4BF]">/</span>
                            <span class="text-[#2DD4BF]">@yield('title')</span>
                        </nav>
                    </div>
                    <div class="flex space-x-3">
                        {{-- Buttons removed as per user request --}}
                    </div>
                </div>

                @yield('content')
                
                <footer class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-400 font-bold tracking-widest uppercase">&copy; {{ date('Y') }} {{ $institution->footer_text ?? 'Antree - Professional Queue Management' }}</p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-xs text-slate-400 font-bold hover:text-teal-500 transition uppercase tracking-widest">Privacy Policy</a>
                        <a href="#" class="text-xs text-slate-400 font-bold hover:text-teal-500 transition uppercase tracking-widest">Term and Conditions</a>
                        <a href="#" class="text-xs text-slate-400 font-bold hover:text-teal-500 transition uppercase tracking-widest">Contact</a>
                    </div>
                </footer>
            </div>
        </main>
    </div>

    {{-- Flash Message Handler --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            iconColor: '#059669', // Emerald-600
            title: '<span class="text-emerald-800">Success!</span>',
            html: '<span class="text-emerald-700 font-medium">{{ session('success') }}</span>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            padding: '1.25rem',
            background: '#ECFDF5', // Emerald-50
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
            iconColor: '#DC2626', // Red-600
            title: '<span class="text-red-800">Oops...</span>',
            html: '<span class="text-red-700 font-medium">{{ session('error') }}</span>',
            confirmButtonColor: '#991B1B', // Red-800
            confirmButtonText: 'Got it',
            padding: '2rem',
            background: '#FEF2F2', // Red-50
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
