@extends('layouts.auth')

@section('title', 'Login')

@section('sky_layout')
    <!-- Sky Background Wrapper -->
    <div class="relative min-h-screen w-full flex items-center justify-center p-4 overflow-hidden select-none" style="background: linear-gradient(180deg, #9bd5f8 0%, #d8eefa 55%, #ffffff 100%);">
        
        <!-- Concentric Arcs -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-[600px] h-[600px] rounded-full border border-white/40 absolute"></div>
            <div class="w-[900px] h-[900px] rounded-full border border-white/25 absolute"></div>
            <div class="w-[1200px] h-[1200px] rounded-full border border-white/10 absolute"></div>
        </div>

        <!-- Soft Cloud Glow Overlays at the bottom -->
        <div class="absolute bottom-0 left-0 w-[500px] h-[250px] bg-white rounded-full blur-[80px] opacity-75 -translate-x-12 translate-y-12 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[300px] bg-white rounded-full blur-[100px] opacity-90 translate-x-12 translate-y-12 pointer-events-none"></div>

        <!-- Brand Logo (Top Left) -->
        <div class="absolute top-8 left-8 flex items-center gap-2.5 z-20">
            <div class="bg-black text-white p-2 rounded-xl flex items-center justify-center w-8 h-8 shadow-sm">
                <!-- Ebolt Clover Icon -->
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2a4 4 0 00-4 4v2H6a4 4 0 000 8h2v2a4 4 0 008 0v-2h2a4 4 0 000-8h-2V6a4 4 0 00-4-4zm-2 6V6a2 2 0 114 0v2h-4zm4 8v2a2 2 0 11-4 0v-2h4zm-6-6h2v4H6a2 2 0 110-4zm10 4v-4h2a2 2 0 110 4h-2z" />
                </svg>
            </div>
            <span class="font-bold text-slate-800 text-lg tracking-tight">Ebolt</span>
        </div>

        <!-- Glassmorphic Login Card -->
        <div class="w-full max-w-[440px] bg-white/40 backdrop-blur-xl border border-white/50 rounded-[2.5rem] p-8 md:p-10 shadow-[0_25px_50px_-12px_rgba(147,197,253,0.35)] relative z-10 flex flex-col items-center">
            
            <!-- Log In Box Header Icon -->
            <div class="bg-white rounded-2xl p-3.5 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-white/60 flex items-center justify-center w-14 h-14 mb-5">
                <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 mb-2 text-center">Sign in with email</h2>
            <p class="text-xs text-slate-500/80 text-center mb-8 max-w-[280px] leading-relaxed">
                Make a new doc to bring your words, data, and teams together. For free
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 w-full" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="w-full">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <div class="relative flex items-center bg-[#f1f5f9]/70 focus-within:bg-white rounded-2xl transition duration-150 ease-in-out px-4 py-3.5 border border-transparent focus-within:border-slate-200/50">
                        <div class="mr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email" class="w-full bg-transparent border-0 p-0 text-sm placeholder-slate-400 focus:ring-0 focus:outline-none text-slate-700"
                            type="email" name="email" :value="old('email')" required autofocus placeholder="Email" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 px-2" />
                </div>

                <!-- Password -->
                <div class="mb-2" x-data="{ show: false }">
                    <div class="relative flex items-center bg-[#f1f5f9]/70 focus-within:bg-white rounded-2xl transition duration-150 ease-in-out px-4 py-3.5 border border-transparent focus-within:border-slate-200/50">
                        <div class="mr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" class="w-full bg-transparent border-0 p-0 text-sm placeholder-slate-400 focus:ring-0 focus:outline-none text-slate-700"
                            ::type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Password" />
                        <button type="button" @click="show = !show" class="ml-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <!-- Eye Icon / Eye Slash Icon -->
                            <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 px-2" />
                </div>

                <!-- Forgot Password -->
                <div class="flex justify-end mb-6">
                    @if (Route::has('password.request'))
                        <a class="text-xs text-slate-500 font-semibold hover:text-slate-800 transition-colors" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="w-full bg-[#1c1b22] hover:bg-black text-white font-semibold py-3.5 px-4 rounded-2xl shadow-sm hover:shadow-md transition duration-150 ease-in-out text-sm mb-6">
                    Get Started
                </button>

                <!-- Divider -->
                <div class="relative flex items-center justify-center mb-6">
                    <div class="flex-grow border-t border-dashed border-slate-300/60"></div>
                    <span class="flex-shrink mx-3 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Or sign in with</span>
                    <div class="flex-grow border-t border-dashed border-slate-300/60"></div>
                </div>

                <!-- Social Logins -->
                <div class="flex space-x-3.5">
                    <!-- Google -->
                    <button type="button" class="flex-1 flex items-center justify-center py-2.5 bg-white rounded-2xl border border-slate-100 hover:bg-slate-50 shadow-[0_2px_8px_rgba(0,0,0,0.02)] transition duration-150 ease-in-out">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#EA4335" d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.2-5.137 4.2-3.415 0-6.19-2.775-6.19-6.19 0-3.415 2.775-6.19 6.19-6.19 1.488 0 2.85.533 3.918 1.41l3.056-3.056C19.043 2.146 15.86 1 12.24 1c-6.075 0-11 4.925-11 11s4.925 11 11 11c5.96 0 10.74-4.8 10.74-11 0-.74-.08-1.44-.22-2.115H12.24Z"/>
                        </svg>
                    </button>
                    <!-- Facebook -->
                    <button type="button" class="flex-1 flex items-center justify-center py-2.5 bg-white rounded-2xl border border-slate-100 hover:bg-slate-50 shadow-[0_2px_8px_rgba(0,0,0,0.02)] transition duration-150 ease-in-out">
                        <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <!-- Apple -->
                    <button type="button" class="flex-1 flex items-center justify-center py-2.5 bg-white rounded-2xl border border-slate-100 hover:bg-slate-50 shadow-[0_2px_8px_rgba(0,0,0,0.02)] transition duration-150 ease-in-out">
                        <svg class="w-5 h-5" fill="black" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.2.67-2.92 1.49-.62.71-1.16 1.85-1.01 2.96 1.12.09 2.27-.57 2.94-1.39z"/>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

