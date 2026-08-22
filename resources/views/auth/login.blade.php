@extends('layouts.auth')

@section('title', 'Login')

@php
    $primaryColor = $displaySettings['primary_color'] ?? '#0D9488';
    $accentColor = $displaySettings['accent_color'] ?? '#1c1b22';
    
    // Parse hex color to RGB for opacity control
    $hex = str_replace('#', '', $primaryColor);
    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $primaryRgb = "$r, $g, $b";

    $logoUrl = null;
    if (!empty($displaySettings['logo_url'])) {
        if (str_starts_with($displaySettings['logo_url'], 'http://') || str_starts_with($displaySettings['logo_url'], 'https://')) {
            $logoUrl = $displaySettings['logo_url'];
        } elseif (str_starts_with($displaySettings['logo_url'], 'storage/')) {
            $logoUrl = asset($displaySettings['logo_url']);
        } else {
            $logoUrl = asset('storage/' . $displaySettings['logo_url']);
        }
    } elseif (!empty($institution->logo_path)) {
        $logoUrl = asset($institution->logo_path);
    }
    
    $appName = $institution->app_name ?? config('app.name', 'Antree');
@endphp

@section('sky_layout')
    <style>
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px) scale(1.05); }
            50% { transform: translateY(20px) scale(1); }
        }
        .animate-float-1 {
            animation: float-slow 8s ease-in-out infinite;
        }
        .animate-float-2 {
            animation: float-delayed 10s ease-in-out infinite;
        }
        .focus-primary-glow:focus-within {
            border-color: {{ $primaryColor }} !important;
            box-shadow: 0 0 0 4px rgba({{ $primaryRgb }}, 0.15), 0 10px 20px -10px rgba({{ $primaryRgb }}, 0.2) !important;
        }
        .btn-premium {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, rgb({{ max(0, $r-30) }}, {{ max(0, $g-30) }}, {{ max(0, $b-30) }}) 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba({{ $primaryRgb }}, 0.5);
            filter: brightness(1.1);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(24px) saturate(120%);
            -webkit-backdrop-filter: blur(24px) saturate(120%);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 30px 60px -15px rgba({{ $primaryRgb }}, 0.12),
                        inset 0 1px 0 rgba(255,255,255,0.6);
        }
    </style>

    <!-- Deep Modern Gradient Base -->
    <div class="relative min-h-screen w-full flex items-center justify-center p-4 overflow-hidden select-none bg-[#f8fafc]">
        
        <!-- Background Decorative Art & Gradients -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <!-- Large Floating Blur Spheres using theme color -->
            <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full blur-[100px] opacity-40 animate-float-1" style="background-color: {{ $primaryColor }};"></div>
            <div class="absolute top-1/2 -right-20 w-[500px] h-[500px] rounded-full blur-[120px] opacity-30 animate-float-2" style="background-color: rgba({{ $primaryRgb }}, 0.75);"></div>
            <div class="absolute -bottom-20 left-1/3 w-80 h-80 rounded-full blur-[90px] opacity-25 animate-float-1" style="background-color: {{ $accentColor }};"></div>

            <!-- Concentric Tech Lines -->
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-full h-full opacity-[0.03]" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <circle cx="50" cy="50" r="40" stroke="{{ $primaryColor }}" stroke-width="0.1" fill="none" />
                    <circle cx="50" cy="50" r="30" stroke="{{ $primaryColor }}" stroke-width="0.08" fill="none" />
                    <circle cx="50" cy="50" r="20" stroke="{{ $primaryColor }}" stroke-width="0.05" fill="none" />
                    <line x1="0" y1="50" x2="100" y2="50" stroke="{{ $primaryColor }}" stroke-width="0.03" />
                    <line x1="50" y1="0" x2="50" y2="100" stroke="{{ $primaryColor }}" stroke-width="0.03" />
                </svg>
            </div>
        </div>

        <!-- Brand Header (Top Center for Balanced Modern Look) -->
        <div class="absolute top-10 flex flex-col items-center gap-2 z-20">
            @if(!empty($logoUrl))
                <div class="p-2.5 bg-white/70 backdrop-blur-md rounded-2xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                    <img src="{{ $logoUrl }}" class="w-9 h-9 object-contain" alt="Logo">
                </div>
            @else
                <div class="bg-black text-white p-2.5 rounded-2xl flex items-center justify-center w-11 h-11 shadow-sm">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a4 4 0 00-4 4v2H6a4 4 0 000 8h2v2a4 4 0 008 0v-2h2a4 4 0 000-8h-2V6a4 4 0 00-4-4zm-2 6V6a2 2 0 114 0v2h-4zm4 8v2a2 2 0 11-4 0v-2h4zm-6-6h2v4H6a2 2 0 110-4zm10 4v-4h2a2 2 0 110 4h-2z" />
                    </svg>
                </div>
            @endif
            <span class="font-bold text-slate-800 text-base tracking-wider uppercase text-center mt-1">{{ $appName }}</span>
        </div>

        <!-- Premium Glassmorphic Card -->
        <div class="w-full max-w-[420px] glass-card rounded-[2.5rem] p-8 md:p-10 relative z-10 flex flex-col items-center mt-12">
            
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-1.5">Welcome Back</h2>
                <p class="text-xs text-slate-500 font-medium">Please sign in to manage your queue services</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 w-full" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="w-full">
                @csrf

                <!-- Email Address -->
                <div class="mb-4.5">
                    <div class="relative flex items-center bg-white/60 focus-within:bg-white rounded-2xl transition duration-300 ease-in-out px-4.5 py-3.5 border border-slate-200/40 focus-primary-glow">
                        <div class="mr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-4.5 w-4.5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email" class="w-full bg-transparent border-0 p-0 text-sm font-semibold placeholder-slate-400/80 focus:ring-0 focus:outline-none text-slate-700"
                            type="email" name="email" :value="old('email')" required autofocus placeholder="Email Address" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 px-2 text-xs font-bold text-rose-500" />
                </div>

                <!-- Password -->
                <div class="mb-3" x-data="{ show: false }">
                    <div class="relative flex items-center bg-white/60 focus-within:bg-white rounded-2xl transition duration-300 ease-in-out px-4.5 py-3.5 border border-slate-200/40 focus-primary-glow">
                        <div class="mr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-4.5 w-4.5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" class="w-full bg-transparent border-0 p-0 text-sm font-semibold placeholder-slate-400/80 focus:ring-0 focus:outline-none text-slate-700"
                            :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Password" />
                        <button type="button" @click="show = !show" class="ml-2 text-slate-400 hover:text-slate-655 transition-colors focus:outline-none">
                            <!-- Eye Icon / Eye Slash Icon -->
                            <svg x-show="!show" class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 px-2 text-xs font-bold text-rose-500" />
                </div>

                <!-- Forgot Password -->
                <div class="flex justify-end mb-6.5">
                    @if (Route::has('password.request'))
                        <a class="text-xs font-bold transition-all text-slate-500 hover:text-slate-800" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Premium Login Button -->
                <button type="submit" class="w-full text-white font-bold py-4 px-4 rounded-2xl shadow-sm text-sm uppercase tracking-wider btn-premium">
                    Sign In
                </button>

                <!-- Divider -->
                <div class="relative flex items-center justify-center my-6">
                    <div class="flex-grow border-t border-dashed border-slate-350/50"></div>
                    <span class="flex-shrink mx-3.5 text-[10px] uppercase tracking-widest text-slate-400/90 font-black">Or sign in with</span>
                    <div class="flex-grow border-t border-dashed border-slate-350/50"></div>
                </div>

                <!-- Social Logins -->
                <div class="flex space-x-3.5">
                    <!-- Google -->
                    <button type="button" class="flex-1 flex items-center justify-center py-3 bg-white/70 hover:bg-white rounded-2xl border border-slate-200/50 shadow-sm transition duration-200 hover:-translate-y-0.5">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24">
                            <path fill="#EA4335" d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.2-5.137 4.2-3.415 0-6.19-2.775-6.19-6.19 0-3.415 2.775-6.19 6.19-6.19 1.488 0 2.85.533 3.918 1.41l3.056-3.056C19.043 2.146 15.86 1 12.24 1c-6.075 0-11 4.925-11 11s4.925 11 11 11c5.96 0 10.74-4.8 10.74-11 0-.74-.08-1.44-.22-2.115H12.24Z"/>
                        </svg>
                    </button>
                    <!-- Facebook -->
                    <button type="button" class="flex-1 flex items-center justify-center py-3 bg-white/70 hover:bg-white rounded-2xl border border-slate-200/50 shadow-sm transition duration-200 hover:-translate-y-0.5">
                        <svg class="w-4.5 h-4.5" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <!-- Apple -->
                    <button type="button" class="flex-1 flex items-center justify-center py-3 bg-white/70 hover:bg-white rounded-2xl border border-slate-200/50 shadow-sm transition duration-200 hover:-translate-y-0.5">
                        <svg class="w-4.5 h-4.5" fill="black" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.2.67-2.92 1.49-.62.71-1.16 1.85-1.01 2.96 1.12.09 2.27-.57 2.94-1.39z"/>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection


