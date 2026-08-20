@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="w-full max-w-sm mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center md:text-left">Welcome</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input id="email" class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out text-slate-700"
                    type="email" name="email" :value="old('email')" required autofocus placeholder="Email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" class="block w-full pl-10 pr-10 py-3 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out text-slate-700"
                    type="password" name="password" required autocomplete="current-password" placeholder="Password" />
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-5 w-5 text-slate-400 hover:text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <!-- Forgot Password -->
            <div class="flex justify-end mb-8">
                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-500 hover:text-blue-700 font-medium" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition duration-150 ease-in-out mb-6">
                Log in
            </button>

            <!-- Divider -->
            <div class="relative flex py-5 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-slate-400 text-sm">Or</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Social Logins Mock -->
            <div class="flex space-x-4 mb-8">
                <button type="button" class="flex-1 flex items-center justify-center py-2 px-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150 ease-in-out">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-5 h-5 mr-2">
                    <span class="text-sm font-medium text-slate-700">Google</span>
                </button>
                <button type="button" class="flex-1 flex items-center justify-center py-2 px-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150 ease-in-out">
                    <img src="https://www.svgrepo.com/show/448226/gitlab.svg" alt="GitLab" class="w-5 h-5 mr-2">
                    <span class="text-sm font-medium text-slate-700">GitLab</span>
                </button>
            </div>

        </form>
    </div>
    </div>
@endsection
