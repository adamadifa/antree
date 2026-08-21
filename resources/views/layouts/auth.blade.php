<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Antree') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bg-auth-blue {
            background-color: #1e3a8a; /* Deep Royal Blue */
        }
        .auth-card {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-slate-100 antialiased min-h-screen">
    @hasSection('sky_layout')
        @yield('sky_layout')
    @else
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="max-w-5xl w-full bg-white rounded-3xl overflow-hidden flex flex-col md:flex-row auth-card min-h-[600px]">
                <!-- Left Side: Hero Section -->
                <div class="hidden md:flex md:w-1/2 bg-auth-blue p-12 flex-col items-center justify-center text-center relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 -mr-16 -mt-16 rounded-full"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 -ml-24 -mb-24 rounded-full"></div>

                    <div class="relative z-10">
                        <img src="{{ asset('images/auth_hero.png') }}" alt="Welcome Astronaut" class="w-64 h-64 mx-auto mb-8 drop-shadow-2xl">
                        <h1 class="text-white text-3xl font-bold mb-4">Welcome aboard my friend</h1>
                        <p class="text-blue-200 text-lg opacity-80">just a couple of clicks and we start</p>
                        
                        <!-- Pagination Dots Mock -->
                        <div class="flex justify-center mt-12 space-x-2">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                            <div class="w-2 h-2 rounded-full bg-white/30"></div>
                            <div class="w-2 h-2 rounded-full bg-white/30"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Form Section -->
                <div class="flex-1 p-8 md:p-16 flex flex-col justify-center">
                    @yield('content')
                </div>
            </div>
        </div>
    @endif
</body>
</html>
