<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $institution->app_name ?? config('app.name', 'Antree') }} Operator - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen flex flex-col">
    <!-- Header -->
    <header class="h-20 bg-white border-b flex items-center justify-between px-8 shadow-sm">
        <div class="flex items-center space-x-4">
            @if(isset($institution) && $institution->logo_path)
                <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-10 h-10 object-contain rounded-xl">
            @else
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white text-2xl shadow-lg shadow-blue-200">A</div>
            @endif
            <div>
                <h1 class="font-bold text-slate-800 leading-none">{{ $institution->app_name ?? 'Antree' }}</h1>
                <span class="text-xs text-slate-400 font-medium">Operator Panel</span>
            </div>
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="text-right">
                <div class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-400">{{ Auth::user()->role === 'operator' ? 'Operator Loket' : 'Administrator' }}</div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </header>

    <main class="flex-1 flex flex-col items-center justify-center p-8">
        <div class="w-full max-w-4xl">
            @yield('content')
        </div>
    </main>
    
    <footer class="p-6 text-center text-slate-400 text-sm">
        &copy; {{ date('Y') }} {{ $institution->footer_text ?? 'Antree - Professional Queue Management' }}
    </footer>
</body>
</html>
