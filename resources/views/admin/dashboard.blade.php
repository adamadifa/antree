@extends('layouts.admin')

@section('title', 'Employee Dashboard')
@section('header', 'Employee Dashboard')

@section('content')
<div class="space-y-5">
    {{-- Top Breadcrumb / Action Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-850 tracking-tight">Employee Dashboard</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-0.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">
                    <svg class="w-3.5 h-3.5 inline mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <span>/</span>
                <span>Dashboard</span>
                <span>/</span>
                <span class="text-slate-600 font-medium">Employee Dashboard</span>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Export</span>
                <svg class="w-3 h-3 text-slate-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ date('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Alert Notice --}}
    <div class="bg-[#EBF5FB] border border-[#D4E6F1] text-[#2471A3] px-4 py-2.5 rounded-xl flex items-center justify-between text-xs font-medium shadow-sm">
        <div class="flex items-center space-x-2">
            <span data-alert-banner>Sistem antrean live aktif: Hari ini tercatat <strong class="font-bold text-[#1B4F72]">{{ $totalToday }} tiket</strong> masuk dengan tingkat penyelesaian <strong class="font-bold text-[#1B4F72]">{{ $completionRate }}%</strong>.</span>
        </div>
        <button class="text-[#2471A3] hover:text-[#1B4F72] text-sm leading-none">&times;</button>
    </div>

    {{-- SECTION 1: Top 3 Cards (Profile Card, Leave/Queue Ratio, Leave/Capacity Stats) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- Profile Card (Col 4) --}}
        <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3.5">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80" alt="Avatar" class="w-12 h-12 rounded-full object-cover ring-2 ring-orange-400 p-0.5">
                        <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="font-bold text-slate-800 text-sm">{{ Auth::user()->name }}</h3>
                            <button class="text-slate-400 hover:text-slate-600">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium">Head Administrator &bull; {{ $institution->name ?? 'Antree System' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                <div>
                    <span class="text-slate-400 text-[11px] block">Phone Number</span>
                    <span class="text-slate-700 font-semibold text-xs">{{ $institution->phone ?? '+62 812 3456 7890' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Email Address</span>
                    <span class="text-slate-700 font-semibold text-xs">{{ Auth::user()->email }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Report Office</span>
                    <span class="text-slate-700 font-semibold text-xs">{{ $institution->name ?? 'Headquarter Antree' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Joined on</span>
                    <span class="text-slate-700 font-semibold text-xs">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : '15 Jan 2024' }}</span>
                </div>
            </div>
        </div>

        {{-- Donut Chart Card: Queue Distribution / Leave Details (Col 5) --}}
        <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-slate-800 text-sm">Queue Details</h3>
                <span class="inline-flex items-center text-[11px] font-semibold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md">
                    <svg class="w-3 h-3 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ date('Y') }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-4 py-2">
                {{-- Legend stats --}}
                <div class="sm:col-span-6 space-y-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                        <span class="text-slate-500 text-[11px]"><strong class="text-slate-800 font-bold" data-stat="completedToday">{{ $completedToday }}</strong> Selesai (Completed)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0"></span>
                        <span class="text-slate-500 text-[11px]"><strong class="text-slate-800 font-bold" data-stat="servingNow">{{ $servingNow }}</strong> Sedang Dilayani</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                        <span class="text-slate-500 text-[11px]"><strong class="text-slate-800 font-bold" data-stat="waitingToday">{{ $waitingToday }}</strong> Menunggu (Waiting)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 flex-shrink-0"></span>
                        <span class="text-slate-500 text-[11px]"><strong class="text-slate-800 font-bold" data-stat="skippedToday">{{ $skippedToday }}</strong> Dilewati (Skipped)</span>
                    </div>
                </div>

                {{-- Donut SVG Graphic --}}
                <div class="sm:col-span-6 flex justify-center items-center relative">
                    @php
                        $tot = max(1, $totalToday);
                        $pComp = ($completedToday / $tot) * 100;
                        $pServ = ($servingNow / $tot) * 100;
                        $pWait = ($waitingToday / $tot) * 100;
                        $pSkip = ($skippedToday / $tot) * 100;
                        
                        // Circle SVG calculations
                        $r = 40;
                        $c = 2 * pi() * $r; // ~251.32
                        $dashComp = ($pComp / 100) * $c;
                        $dashServ = ($pServ / 100) * $c;
                        $dashWait = ($pWait / 100) * $c;
                        $dashSkip = ($pSkip / 100) * $c;
                        
                        $offset1 = 0;
                        $offset2 = -$dashComp;
                        $offset3 = -($dashComp + $dashServ);
                        $offset4 = -($dashComp + $dashServ + $dashWait);
                    @endphp
                    <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="{{ $r }}" stroke="#F1F5F9" stroke-width="14" fill="transparent" />
                        {{-- Completed segment --}}
                        <circle cx="50" cy="50" r="{{ $r }}" stroke="#10B981" stroke-width="14" fill="transparent" 
                                stroke-dasharray="{{ $dashComp }} {{ $c }}" stroke-dashoffset="{{ $offset1 }}" />
                        {{-- Serving segment --}}
                        <circle cx="50" cy="50" r="{{ $r }}" stroke="#3B82F6" stroke-width="14" fill="transparent" 
                                stroke-dasharray="{{ $dashServ }} {{ $c }}" stroke-dashoffset="{{ $offset2 }}" />
                        {{-- Waiting segment --}}
                        <circle cx="50" cy="50" r="{{ $r }}" stroke="#F59E0B" stroke-width="14" fill="transparent" 
                                stroke-dasharray="{{ $dashWait }} {{ $c }}" stroke-dashoffset="{{ $offset3 }}" />
                        {{-- Skipped segment --}}
                        <circle cx="50" cy="50" r="{{ $r }}" stroke="#EF4444" stroke-width="14" fill="transparent" 
                                stroke-dasharray="{{ $dashSkip }} {{ $c }}" stroke-dashoffset="{{ $offset4 }}" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xs font-bold text-slate-800" data-stat="totalToday">{{ $totalToday }}</span>
                        <span class="text-[9px] text-slate-400 font-medium">Antrean</span>
                    </div>
                </div>
            </div>

            <div class="mt-2 pt-3 border-t border-slate-100 flex items-center text-xs text-slate-600">
                <svg class="w-4 h-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Better than <strong class="text-slate-800 font-bold">85%</strong> of historical traffic load</span>
            </div>
        </div>

        {{-- Leave / Quota Details (Col 3) --}}
        <div class="lg:col-span-3 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-slate-800 text-sm">Summary Totals</h3>
                <span class="inline-flex items-center text-[11px] font-semibold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md">
                    <svg class="w-3 h-3 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ date('Y') }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-y-3 gap-x-2 text-xs">
                <div>
                    <span class="text-slate-400 text-[11px] block">Total Antrian</span>
                    <span class="text-base font-extrabold text-slate-800" data-stat="totalToday">{{ $totalToday }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Selesai</span>
                    <span class="text-base font-extrabold text-slate-800" data-stat="completedToday">{{ $completedToday }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Menunggu</span>
                    <span class="text-base font-extrabold text-slate-800" data-stat="waitingToday">{{ $waitingToday }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Dilewati</span>
                    <span class="text-base font-extrabold text-slate-800" data-stat="skippedToday">{{ $skippedToday }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Weekly Total</span>
                    <span class="text-base font-extrabold text-slate-800" data-stat="totalWeek">{{ $totalWeek }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[11px] block">Active Loket</span>
                    <span class="text-base font-extrabold text-slate-800">{{ $counters->count() }}</span>
                </div>
            </div>

            <div class="mt-4 pt-2">
                <a href="{{ route('admin.service-types.index') }}" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold flex items-center justify-center transition shadow-sm">
                    Manage Service Types
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 1.5: 4 Metric Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Today --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center space-x-3.5 hover:border-orange-200 transition duration-200">
            <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Hari Ini</span>
                <span class="text-xl font-extrabold text-slate-800 leading-tight block mt-0.5" data-stat="totalToday">{{ $totalToday }}</span>
            </div>
        </div>

        {{-- Card 2: Week --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center space-x-3.5 hover:border-slate-300 transition duration-200">
            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Minggu Ini</span>
                <span class="text-xl font-extrabold text-slate-800 leading-tight block mt-0.5" data-stat="totalWeek">{{ $totalWeek }}</span>
            </div>
        </div>

        {{-- Card 3: Month --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center space-x-3.5 hover:border-blue-200 transition duration-200">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Bulan Ini</span>
                <span class="text-xl font-extrabold text-slate-800 leading-tight block mt-0.5" data-stat="totalMonth">{{ $totalMonth }}</span>
            </div>
        </div>

        {{-- Card 4: Overtime / Speed --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center space-x-3.5 hover:border-rose-200 transition duration-200">
            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Layanan</span>
                <span class="text-xl font-extrabold text-slate-800 leading-tight block mt-0.5" data-stat="avgServiceTime">{{ $avgServiceTime }}</span>
            </div>
        </div>
    </div>

    {{-- SECTION 2: Attendance & Time Tracker --}}
    <div class="grid grid-cols-1 gap-5">
        {{-- Attendance / Clock-in Card --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex items-center justify-center">
                    <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                        <circle cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" stroke-linecap="round" fill="transparent" 
                                stroke-dasharray="251.32" stroke-dashoffset="62.83" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-[8px] font-semibold text-slate-400">Hari Ini</span>
                        <span class="text-[11px] font-black text-slate-800">{{ $totalToday }}</span>
                    </div>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Service Clock</span>
                    <h4 class="text-sm font-bold text-slate-800" id="live-clock">{{ date('h:i:s A, d M Y') }}</h4>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="bg-slate-900 text-white text-[10px] font-medium px-3 py-1.5 rounded-lg">
                    Avg Service: <span class="font-bold text-emerald-400">{{ $avgServiceTime }}</span>
                </div>
                <a href="{{ route('operator.index') }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-rose-500 hover:from-orange-600 hover:to-rose-600 text-white font-bold text-xs rounded-lg shadow-sm shadow-orange-500/20 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span>Buka Loket Operator</span>
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 3: Projects (Counters) + Tasks (Recent Queues) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- Projects Column: Active Loket & Counters (Col 5) --}}
        <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-start">
            <div class="flex items-center justify-between mb-4 flex-shrink-0">
                <h3 class="font-bold text-slate-800 text-sm">Loket Operasional</h3>
                <div class="relative">
                    <button class="inline-flex items-center space-x-1 text-xs font-semibold text-slate-500 hover:text-slate-700">
                        <span>Ongoing Counters</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
                @forelse($counters as $counter)
                <div class="border border-slate-200/70 rounded-xl p-4 flex flex-col justify-between bg-white hover:border-orange-200 hover:shadow-md hover:shadow-orange-500/5 transition duration-200">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            {{ $counter->name }}
                        </h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider
                            {{ $counter->status === 'online' ? 'text-emerald-700 bg-emerald-50 border border-emerald-100' : ($counter->status === 'break' ? 'text-amber-700 bg-amber-50 border border-amber-100' : 'text-slate-500 bg-slate-50 border border-slate-200') }}">
                            {{ $counter->status }}
                        </span>
                    </div>

                    <div class="flex items-center space-x-2.5 my-3.5">
                        <div class="w-9 h-9 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center font-extrabold text-xs text-orange-600 uppercase">
                            {{ substr($counter->operator->name ?? 'OP', 0, 2) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ $counter->operator->name ?? 'Staff Operator' }}</p>
                            <span class="text-[10px] text-slate-400 font-medium">ID #{{ $counter->id }} &bull; Layanan: {{ $counter->serviceType->code ?? 'SVC' }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Selesai Dilayani:</span>
                        <strong class="text-slate-800 font-extrabold">{{ $counter->served_count ?? 0 }}</strong>
                    </div>

                    <div class="pt-2.5 mt-1 flex items-center justify-between">
                        <span class="text-[11px] text-slate-500 font-medium">Antrean Aktif:</span>
                        @if($counter->current_queue)
                            <span class="text-xs font-bold px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-md font-mono animate-pulse">{{ $counter->current_queue->queue_number }}</span>
                        @else
                            <span class="text-[10px] font-semibold px-2 py-0.5 bg-slate-100 text-slate-500 border border-slate-200 rounded-md">Kosong (Standby)</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-2 py-8 text-center text-slate-400 text-xs italic">
                    Belum ada data loket terdaftar.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Tasks Column: Recent Queues List (Col 7) --}}
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-start">
            <div class="flex items-center justify-between mb-4 flex-shrink-0">
                <h3 class="font-bold text-slate-800 text-sm">Tasks & Live Queues</h3>
                <div class="relative">
                    <button class="inline-flex items-center space-x-1 text-xs font-semibold text-slate-500 hover:text-slate-700">
                        <span>All Queues</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
            </div>

            <div class="space-y-2.5 overflow-x-auto flex-1" data-recent-queues>
                @forelse($recentQueues->take(5) as $q)
                <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 hover:bg-slate-50/80 transition duration-150 group">
                    <div class="flex items-center space-x-3">
                        <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <div>
                            <span class="text-xs font-bold text-slate-800">{{ $q->queue_number }} - {{ $q->serviceType->name }}</span>
                            <span class="text-[10px] text-slate-400 ml-1">({{ $q->customer_name ?? 'Pelanggan' }})</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3.5">
                        @if($q->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100">
                                &bull; Completed
                            </span>
                        @elseif($q->status === 'serving' || $q->status === 'calling')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-100">
                                &bull; Inprogress
                            </span>
                        @elseif($q->status === 'waiting')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100">
                                &bull; Onhold
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100">
                                &bull; Skipped
                            </span>
                        @endif

                        <div class="w-6 h-6 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-6 text-center text-slate-400 text-xs italic">
                    Belum ada aktivitas antrean hari ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SECTION 4: Performance Curve + Skills/Services + Birthday/Leave Policy Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- Performance Area Chart (Col 4) --}}
        <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-slate-800 text-sm">Performance Curve</h3>
                <span class="inline-flex items-center text-[11px] font-semibold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md">
                    7 Days
                </span>
            </div>

            <div class="flex items-baseline space-x-2 mb-2">
                <span class="text-2xl font-black text-slate-800">{{ $completionRate }}%</span>
                <span class="text-[11px] text-slate-400">Rasio Selesai Hari Ini</span>
            </div>

            {{-- Dynamic SVG Smooth Curve Area Chart using real chartData --}}
            <div class="relative h-32 w-full my-2">
                @if($chartData->count() > 0)
                @php
                    $maxCount = max($chartData->max(), 1);
                    $chartHeight = 80;
                    $chartWidth = 280;
                    $points = [];
                    $dates = $chartData->keys();
                    $counts = $chartData->values();
                    $stepX = $chartWidth / (max(count($dates) - 1, 1));
                    for($i = 0; $i < count($dates); $i++) {
                        $x = $i * $stepX;
                        $y = 90 - ($counts[$i] / $maxCount * $chartHeight);
                        $points[] = "$x,$y";
                    }
                    $pathData = "M" . implode(" L", $points);
                    $areaData = $pathData . " L" . ($chartWidth) . ",100 L0,100 Z";
                @endphp
                <svg class="w-full h-full" viewBox="0 0 280 100" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="curveGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10B981" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#10B981" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <path d="{{ $areaData }}" fill="url(#curveGrad)"/>
                    <path d="{{ $pathData }}" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                @else
                <div class="h-full flex items-center justify-center text-slate-400 text-xs italic">
                    Belum ada data kunjungan.
                </div>
                @endif
            </div>

            <div class="flex justify-between text-[9px] text-slate-400 font-bold pt-2 border-t border-slate-100 uppercase tracking-wider">
                @foreach($chartData as $date => $count)
                    <span>{{ \Carbon\Carbon::parse($date)->format('d M') }}</span>
                @endforeach
            </div>

            <p class="text-[10px] text-slate-400 text-center mt-3">Statistik data kunjungan antrean 7 hari terakhir</p>
        </div>

        {{-- My Skills / Service Categories Progress (Col 4) --}}
        <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-slate-800 text-sm">Services & Categories</h3>
                <span class="inline-flex items-center text-[11px] font-semibold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md">
                    <svg class="w-3 h-3 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ date('Y') }}
                </span>
            </div>

            <div class="space-y-3 flex-1 flex flex-col justify-center">
                @forelse($serviceStats->take(4) as $service)
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 leading-tight">{{ $service->name }}</h4>
                        <span class="text-[10px] text-slate-400">Total Tiket: {{ $service->queues_count }}</span>
                    </div>
                    <div class="relative w-8 h-8 flex items-center justify-center">
                        <svg class="w-8 h-8 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="text-rose-500" stroke-dasharray="{{ $service->percentage }}, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                        <span class="absolute text-[8px] font-bold text-slate-700">{{ $service->percentage }}%</span>
                    </div>
                </div>
                @empty
                <div class="py-6 text-center text-slate-400 text-xs italic">
                    Belum ada divisi layanan.
                </div>
                @endforelse
            </div>

            <div class="mt-3 pt-2 text-[10px] text-slate-400 text-right border-t border-slate-100/80">
                Last Update on {{ date('d M Y') }}
            </div>
        </div>

        {{-- Team Members (Col 4) --}}
        <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4 flex-shrink-0">
                <h3 class="font-bold text-slate-800 text-sm">Team Members</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">View All</a>
            </div>

            <div class="space-y-3.5 flex-1 flex flex-col justify-center">
                @forelse($teamMembers->take(4) as $member)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-600 uppercase ring-1 ring-slate-200/50">
                            {{ substr($member->name, 0, 2) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 leading-tight">{{ $member->name }}</h4>
                            <p class="text-[10px] text-slate-400">{{ ucfirst($member->role) }} &bull; {{ $member->counter->name ?? 'Head Office' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-6 text-center text-slate-400 text-xs italic">
                    Belum ada anggota tim terdaftar.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SECTION 5: Bottom Columns (Notifications) --}}
    <div class="grid grid-cols-1 gap-5">
        {{-- Notifications & Call Logs --}}
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4 flex-shrink-0">
                <h3 class="font-bold text-slate-800 text-sm">Notifications / Call Logs</h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">View All</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4" data-recent-activities>
                @forelse($recentActivities->take(4) as $act)
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-start space-x-3 hover:bg-slate-100/50 transition">
                    <div class="w-7 h-7 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center font-extrabold text-[10px] text-orange-600 uppercase mt-0.5">
                        {{ substr($act->counter->name ?? 'L', 0, 2) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-700 leading-snug">
                            <strong class="font-bold text-slate-900">{{ $act->counter->name ?? 'Loket' }}</strong> memanggil <strong class="font-bold text-rose-500">{{ $act->queue_number }}</strong>
                        </p>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Hari ini pukul {{ $act->called_at ? $act->called_at->format('h:i A') : '09:00 AM' }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-4 py-6 text-center text-slate-400 text-xs italic">
                    Belum ada notifikasi panggilan hari ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live Clock Update
    function updateClock() {
        const now = new Date();
        const options = { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: true, 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        };
        const clockEl = document.getElementById('live-clock');
        if (clockEl) {
            clockEl.innerText = now.toLocaleDateString('en-US', options);
        }
    }
    setInterval(updateClock, 1000);

    // ─── AJAX Realtime Dashboard Update ───
    function refreshDashboardData() {
        fetch('{{ route("admin.dashboard.api-data") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            // Update stat numbers via data-stat attributes
            document.querySelectorAll('[data-stat]').forEach(el => {
                const key = el.getAttribute('data-stat');
                if (data[key] !== undefined) {
                    el.textContent = data[key];
                }
            });

            // Update alert banner
            const alertEl = document.querySelector('[data-alert-banner]');
            if (alertEl) {
                alertEl.innerHTML = `Sistem antrean live aktif: Hari ini tercatat <strong class="font-bold text-[#1B4F72]">${data.totalToday} tiket</strong> masuk dengan tingkat penyelesaian <strong class="font-bold text-[#1B4F72]">${data.completionRate}%</strong>.`;
            }

            // Update recent queues list
            const queueList = document.querySelector('[data-recent-queues]');
            if (queueList && data.recentQueues) {
                if (data.recentQueues.length === 0) {
                    queueList.innerHTML = '<div class="py-6 text-center text-slate-400 text-xs italic">Belum ada aktivitas antrean hari ini.</div>';
                } else {
                    queueList.innerHTML = data.recentQueues.map(q => {
                        let statusBadge = '';
                        if (q.status === 'completed') {
                            statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100">&bull; Completed</span>';
                        } else if (q.status === 'serving' || q.status === 'calling') {
                            statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-100">&bull; Inprogress</span>';
                        } else if (q.status === 'waiting') {
                            statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100">&bull; Onhold</span>';
                        } else {
                            statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100">&bull; Skipped</span>';
                        }
                        return `<div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 hover:bg-slate-50/80 transition duration-150 group">
                            <div class="flex items-center space-x-3">
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <div>
                                    <span class="text-xs font-bold text-slate-800">${q.queue_number} - ${q.service_name}</span>
                                    <span class="text-[10px] text-slate-400 ml-1">(${q.customer_name})</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3.5">
                                ${statusBadge}
                                <div class="w-6 h-6 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            </div>
                        </div>`;
                    }).join('');
                }
            }

            // Update recent activities / notifications
            const actList = document.querySelector('[data-recent-activities]');
            if (actList && data.recentActivities) {
                if (data.recentActivities.length === 0) {
                    actList.innerHTML = '<div class="col-span-4 py-6 text-center text-slate-400 text-xs italic">Belum ada notifikasi panggilan hari ini.</div>';
                } else {
                    actList.innerHTML = data.recentActivities.map(act => `
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-start space-x-3 hover:bg-slate-100/50 transition">
                            <div class="w-7 h-7 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center font-extrabold text-[10px] text-orange-600 uppercase mt-0.5">${act.counter_initials}</div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-700 leading-snug"><strong class="font-bold text-slate-900">${act.counter_name}</strong> memanggil <strong class="font-bold text-rose-500">${act.queue_number}</strong></p>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Hari ini pukul ${act.called_at}</span>
                            </div>
                        </div>
                    `).join('');
                }
            }

            console.log('✅ Dashboard data refreshed via AJAX');
        })
        .catch(err => console.error('Dashboard refresh error:', err));
    }

    // Realtime Reverb WebSockets integration via Laravel Echo
    window.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel('queue-channel')
                .listen('.queue.called', (e) => {
                    console.log('Realtime Call event received:', e);
                    refreshDashboardData();
                })
                .listen('.queue.created', (e) => {
                    console.log('Realtime Created event received:', e);
                    refreshDashboardData();
                });
        } else {
            console.error('Laravel Echo not found. Realtime updates disabled.');
        }
    });
</script>
@endpush
