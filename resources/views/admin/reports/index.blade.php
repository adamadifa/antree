@extends('layouts.admin')

@section('title', 'Laporan Antrean')
@section('header', 'Laporan & Analytics')

@section('content')
<div class="space-y-8">
    
    <!-- Filter Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                <!-- Tanggal Mulai -->
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-teal-500 transition pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="text" name="start_date" id="start_date" value="{{ $startDate }}" placeholder="Tanggal Mulai" class="flatpickr w-full h-11 pl-12 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-4 focus:ring-teal-50/75 focus:border-teal-500 outline-none transition duration-155">
                </div>

                <!-- Tanggal Selesai -->
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-teal-500 transition pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="text" name="end_date" id="end_date" value="{{ $endDate }}" placeholder="Tanggal Selesai" class="flatpickr w-full h-11 pl-12 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-4 focus:ring-teal-50/75 focus:border-teal-500 outline-none transition duration-155">
                </div>

                <!-- Divisi Layanan -->
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-teal-500 transition pointer-events-none">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </span>
                    <select name="service_type_id" class="w-full h-11 pl-12 pr-10 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-4 focus:ring-teal-50/75 focus:border-teal-500 outline-none appearance-none transition duration-155">
                        <option value="">Semua Divisi Layanan</option>
                        @foreach($serviceTypes as $st)
                            <option value="{{ $st->id }}" {{ request('service_type_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Status Antrean -->
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-teal-500 transition pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <select name="status" class="w-full h-11 pl-12 pr-10 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-4 focus:ring-teal-50/75 focus:border-teal-500 outline-none appearance-none transition duration-155">
                        <option value="">Semua Status Antrean</option>
                        <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                        <option value="calling" {{ request('status') == 'calling' ? 'selected' : '' }}>Calling</option>
                        <option value="serving" {{ request('status') == 'serving' ? 'selected' : '' }}>Serving</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="skipped" {{ request('status') == 'skipped' ? 'selected' : '' }}>Skipped</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 h-11 bg-teal-600 text-white rounded-xl font-bold text-xs hover:bg-teal-700 active:scale-[0.98] transition duration-150 flex items-center justify-center space-x-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.reports.export', request()->all()) }}" class="h-11 px-6 bg-slate-900 text-white rounded-xl hover:bg-slate-800 active:scale-[0.98] transition duration-150 flex items-center justify-center space-x-2 shadow-sm" title="Export CSV">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span class="text-xs font-bold">CSV</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards (Matching Dashboard Accent colors) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Total Antrean -->
        <div class="bg-teal-600 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-250 flex items-center justify-between text-white">
            <div>
                <span class="text-[11px] font-bold text-teal-100 uppercase tracking-wider block mb-1">Total Antrean</span>
                <span class="text-3xl font-extrabold text-white tracking-tight block">{{ number_format($totalQueues) }}</span>
                <span class="text-[10px] font-semibold text-teal-100/90 block mt-1">Akumulasi tiket masuk</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-500/40 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Rata-rata Tunggu -->
        <div class="bg-blue-600 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-250 flex items-center justify-between text-white">
            <div>
                <span class="text-[11px] font-bold text-blue-100 uppercase tracking-wider block mb-1">Rata-rata Tunggu</span>
                <span class="text-3xl font-extrabold text-white tracking-tight block">
                    {{ floor($avgWaitTime / 60) }}<span class="text-base font-semibold text-blue-200">m</span> {{ round($avgWaitTime % 60) }}<span class="text-base font-semibold text-blue-200">s</span>
                </span>
                <span class="text-[10px] font-semibold text-blue-100/90 block mt-1">Waktu respon loket</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-500/40 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Rata-rata Layanan -->
        <div class="bg-violet-600 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-250 flex items-center justify-between text-white">
            <div>
                <span class="text-[11px] font-bold text-violet-100 uppercase tracking-wider block mb-1">Rata-rata Layanan</span>
                <span class="text-3xl font-extrabold text-white tracking-tight block">
                    {{ floor($avgServiceTime / 60) }}<span class="text-base font-semibold text-violet-200">m</span> {{ round($avgServiceTime % 60) }}<span class="text-base font-semibold text-violet-200">s</span>
                </span>
                <span class="text-[10px] font-semibold text-violet-100/90 block mt-1">Durasi proses di operator</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-violet-500/40 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider pl-6">Waktu Masuk</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Antrian</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Divisi Layanan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Loket Panggil</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider pr-6 text-right">Durasi Layanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($queues as $q)
                    <tr class="hover:bg-slate-50/50 transition duration-150">
                        <td class="px-6 py-4 pl-6 text-xs font-semibold text-slate-500">
                            <span class="text-slate-800">{{ $q->created_at->format('d M Y') }}</span>
                            <span class="block text-[10px] font-medium text-slate-400 mt-0.5">{{ $q->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-900 text-white text-xs font-bold rounded-lg shadow-sm">{{ $q->queue_number }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $q->serviceType->name }}</td>
                        <td class="px-6 py-4">
                            @if($q->counter)
                                <div class="text-xs font-bold text-slate-700">{{ $q->counter->name }}</div>
                                <div class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $q->counter->operator->name ?? '-' }}</div>
                            @else
                                <span class="text-slate-355 italic text-[11px]">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'waiting' => 'bg-slate-50 border border-slate-200 text-slate-500',
                                    'calling' => 'bg-amber-50 border border-amber-100 text-amber-700',
                                    'serving' => 'bg-teal-50 border border-teal-100 text-teal-700',
                                    'completed' => 'bg-emerald-50 border border-emerald-100 text-emerald-700',
                                    'skipped' => 'bg-rose-50 border border-rose-100 text-rose-700',
                                ];
                                $statusPills = [
                                    'waiting' => 'bg-slate-400',
                                    'calling' => 'bg-amber-500',
                                    'serving' => 'bg-teal-500',
                                    'completed' => 'bg-emerald-500',
                                    'skipped' => 'bg-rose-500',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase {{ $statusClasses[$q->status] ?? 'bg-slate-50 border border-slate-200 text-slate-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $statusPills[$q->status] ?? 'bg-slate-400' }}"></span>
                                {{ $q->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 pr-6 text-right">
                            @if($q->called_at && $q->completed_at)
                                @php $diff = $q->called_at->diffInSeconds($q->completed_at); @endphp
                                <span class="text-xs font-bold text-slate-700">{{ floor($diff / 60) }}m {{ $diff % 60 }}s</span>
                            @else
                                <span class="text-slate-355 italic text-[11px]">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m3 2h12a3 3 0 003-3V7a3 3 0 00-3-3H4a3 3 0 00-3 3v10a3 3 0 003 3z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tidak ada data untuk periode ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($queues->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $queues->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
    <!-- Flatpickr CSS & JS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .flatpickr-calendar {
            border-radius: 1rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025) !important;
            border: 1px solid #e2e8f0 !important;
            padding: 0.25rem;
            font-family: inherit;
        }
        .flatpickr-day.selected {
            background: #0d9488 !important;
            border-color: #0d9488 !important;
            color: #ffffff !important;
        }
        .flatpickr-day.selected:hover {
            background: #0f766e !important;
            border-color: #0f766e !important;
        }
        .flatpickr-day:hover {
            background: #f1f5f9 !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #1e293b !important;
            fill: #1e293b !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700 !important;
        }
        span.flatpickr-weekday {
            font-weight: 700 !important;
            color: #64748b !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'j F Y',
                allowInput: false
            });
        });
    </script>
@endpush

