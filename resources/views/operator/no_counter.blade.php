@extends('layouts.operator')

@section('title', 'Operator Panel')

@section('content')
<style>
    .action-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -4px rgba(0, 0, 0, 0.1);
    }
    .action-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        pointer-events: none;
    }
    .card-flare {
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.3);
        filter: blur(40px);
        border-radius: 50%;
        pointer-events: none;
    }
</style>

<div class="flex-1 flex items-center justify-center">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="action-card w-20 h-20 rounded-2xl mx-auto flex items-center justify-center text-white" style="background: linear-gradient(135deg, #EF4444dd, #DC2626f2);">
            <div class="card-flare"></div>
            <i class="ti ti-shield-lock text-3xl relative z-10"></i>
        </div>
        
        <div class="space-y-2">
            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Akses Terbatas</h3>
            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-sm mx-auto">
                Akun Anda saat ini belum terhubung ke loket mana pun. Silakan hubungi administrator sistem untuk menetapkan Anda ke loket yang tersedia.
            </p>
        </div>

        <div class="flex flex-col gap-3 pt-4 max-w-xs mx-auto">
            <a href="{{ route('dashboard') }}" class="action-card w-full py-3.5 rounded-xl text-white font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #1E293Bdd, #0F172Af2);">
                <div class="card-flare"></div>
                <i class="ti ti-arrow-left text-sm relative z-10"></i>
                <span class="relative z-10">Kembali ke Dashboard</span>
            </a>
            <button onclick="window.location.reload()" class="w-full py-3.5 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                <i class="ti ti-refresh text-sm"></i>
                Muat Ulang
            </button>
        </div>
    </div>
</div>
@endsection
