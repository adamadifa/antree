<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $institution->app_name ?? config('app.name', 'Antree') }} Operator - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            background-image: radial-gradient(#94A3B8 0.5px, transparent 0.5px);
            background-size: 32px 32px;
            color: #0F172A;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .fade-up {
            animation: fadeUp 0.6s ease-out both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased h-screen w-screen overflow-hidden flex flex-col select-none">
    
    <!-- Header Bar -->
    <header class="mx-6 mt-6 bg-white/90 backdrop-blur-md px-6 py-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between shrink-0 fade-up">
        <!-- Left: Brand -->
        <div class="flex items-center gap-3">
            @if(isset($institution) && $institution->logo_path)
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1 border border-slate-100 shrink-0">
                    <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
                </div>
            @else
                <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-white shrink-0">
                    <i class="ti ti-bolt text-lg"></i>
                </div>
            @endif
            <div>
                <h1 class="text-base font-bold text-slate-800 tracking-tight leading-tight">{{ $institution->app_name ?? 'Antree' }}</h1>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Operator Panel</span>
                </div>
            </div>
        </div>

        <!-- Center: Clock -->
        <div class="hidden md:flex items-center gap-6">
            <div class="text-center">
                <p id="nav-time" class="text-xl font-extrabold text-slate-800 tabular-nums leading-none mb-1">00:00:00</p>
                <p id="nav-date" class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none">Jum, 21 Agt 2026</p>
            </div>
        </div>

        <!-- Right: User & Logout -->
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="text-xs font-bold text-slate-800 leading-none">{{ Auth::user()->name }}</div>
                <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ Auth::user()->role === 'operator' ? 'Operator' : 'Admin' }}</div>
            </div>
            <div class="h-6 w-px bg-slate-200"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition" title="Logout">
                    <i class="ti ti-logout text-sm"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 overflow-hidden p-6 w-full max-w-[1400px] mx-auto flex flex-col justify-stretch fade-up" style="animation-delay:0.05s">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="pb-5 text-center shrink-0">
        <p class="text-[10px] font-semibold text-slate-400">&copy; {{ date('Y') }} {{ $institution->footer_text ?? 'Antree - Sistem Antrean Mandiri' }}</p>
    </footer>

    <!-- Clock Script -->
    <script>
        function tick() {
            const now = new Date();
            const t = document.getElementById('nav-time');
            const d = document.getElementById('nav-date');
            if (t) t.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            if (d) d.textContent = now.toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' });
        }
        tick(); setInterval(tick, 1000);
    </script>

    <!-- Flash Messages -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success', iconColor: '#10B981',
            title: '<span style="font-size:15px;font-weight:800;color:#0F172A">Berhasil!</span>',
            html: '<span style="font-size:12px;color:#64748B">{{ session('success') }}</span>',
            showConfirmButton: false, timer: 2200, timerProgressBar: true,
            toast: true, position: 'top-end', padding: '1rem',
            background: '#fff',
            customClass: { popup: 'rounded-2xl shadow-xl border border-slate-200', timerProgressBar: 'bg-emerald-500' }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error', iconColor: '#EF4444',
            title: '<span style="font-size:15px;font-weight:800;color:#0F172A">Error</span>',
            html: '<span style="font-size:12px;color:#64748B">{{ session('error') }}</span>',
            confirmButtonColor: '#0F172A', confirmButtonText: 'Mengerti',
            padding: '1.5rem', background: '#fff',
            customClass: { popup: 'rounded-2xl shadow-xl border border-slate-200', confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-xs' }
        });
    </script>
    @endif
    @stack('scripts')
</body>
</html>
