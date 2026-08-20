@extends('layouts.admin')

@section('title', 'Operator Panel')
@section('header', 'Operator Access')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="max-w-md w-full text-center">
        <div class="w-24 h-24 bg-red-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 border border-red-100">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        
        <h3 class="text-3xl font-black text-slate-800 mb-4 tracking-tight">Access Restricted</h3>
        <p class="text-slate-500 font-medium leading-relaxed mb-10">
            Your account is currenty not assigned to any active counter. Please contact your system administrator to assign you to a specific counter (Loket).
        </p>

        <div class="flex flex-col gap-4">
            <a href="{{ route('dashboard') }}" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-xl hover:bg-slate-800 transition duration-300 uppercase tracking-widest text-xs">
                Back to Dashboard
            </a>
            <button onclick="window.location.reload()" class="w-full bg-white text-slate-600 font-bold py-4 rounded-2xl border border-slate-200 hover:bg-slate-50 transition duration-300 uppercase tracking-widest text-xs">
                Refresh Page
            </button>
        </div>
    </div>
</div>
@endsection
