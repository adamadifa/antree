@extends('layouts.admin')

@section('title', 'Laporan Antrean')
@section('header', 'Laporan & Analytics')

@section('content')
<!-- Import Tabler Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

<div class="space-y-6">
    {{-- Header Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Reports & Analytics</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-655 transition">
                    <i class="ti ti-home text-sm align-middle"></i>
                </a>
                <span>/</span>
                <span>Reports & Analytics</span>
                <span>/</span>
                <span class="text-rose-500 font-semibold">Queue Report</span>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                <!-- Tanggal Mulai -->
                <div class="relative">
                    <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500">
                        Start Date
                    </label>
                    <div class="flex items-center border border-slate-200 rounded-xl px-3.5 py-1.5 bg-white transition duration-200 focus-within:border-rose-400 focus-within:ring-4 focus-within:ring-rose-50">
                        <i class="ti ti-calendar-event text-slate-400 text-base mr-2.5 shrink-0"></i>
                        <input type="text" name="start_date" id="start_date" value="{{ $startDate }}" placeholder="Select date" class="flatpickr w-full bg-transparent text-xs font-semibold text-slate-700 border-0 focus:ring-0 outline-none py-1 placeholder:text-slate-400/60">
                    </div>
                </div>

                <!-- Tanggal Selesai -->
                <div class="relative">
                    <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500">
                        End Date
                    </label>
                    <div class="flex items-center border border-slate-200 rounded-xl px-3.5 py-1.5 bg-white transition duration-200 focus-within:border-rose-400 focus-within:ring-4 focus-within:ring-rose-50">
                        <i class="ti ti-calendar-event text-slate-400 text-base mr-2.5 shrink-0"></i>
                        <input type="text" name="end_date" id="end_date" value="{{ $endDate }}" placeholder="Select date" class="flatpickr w-full bg-transparent text-xs font-semibold text-slate-700 border-0 focus:ring-0 outline-none py-1 placeholder:text-slate-400/60">
                    </div>
                </div>

                <!-- Divisi Layanan -->
                <div class="relative">
                    <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500">
                        Service Category
                    </label>
                    <div class="flex items-center border border-slate-200 rounded-xl px-3.5 py-1.5 bg-white transition duration-200 focus-within:border-rose-400 focus-within:ring-4 focus-within:ring-rose-50">
                        <i class="ti ti-category text-slate-400 text-base mr-2.5 shrink-0"></i>
                        <select name="service_type_id" class="w-full bg-transparent text-xs font-semibold text-slate-705 border-0 focus:ring-0 outline-none py-1 cursor-pointer">
                            <option value="">Semua Divisi Layanan</option>
                            @foreach($serviceTypes as $st)
                                <option value="{{ $st->id }}" {{ request('service_type_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status Antrean -->
                <div class="relative">
                    <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500">
                        Queue Status
                    </label>
                    <div class="flex items-center border border-slate-200 rounded-xl px-3.5 py-1.5 bg-white transition duration-200 focus-within:border-rose-400 focus-within:ring-4 focus-within:ring-rose-50">
                        <i class="ti ti-activity text-slate-400 text-base mr-2.5 shrink-0"></i>
                        <select name="status" class="w-full bg-transparent text-xs font-semibold text-slate-705 border-0 focus:ring-0 outline-none py-1 cursor-pointer">
                            <option value="">Semua Status Antrean</option>
                            <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                            <option value="calling" {{ request('status') == 'calling' ? 'selected' : '' }}>Calling</option>
                            <option value="serving" {{ request('status') == 'serving' ? 'selected' : '' }}>Serving</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="skipped" {{ request('status') == 'skipped' ? 'selected' : '' }}>Skipped</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 h-10 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-xs transition duration-150 flex items-center justify-center space-x-1.5 shadow-sm shadow-rose-500/10 uppercase tracking-wider">
                        <i class="ti ti-filter text-sm"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.reports.export', request()->all()) }}" class="h-10 px-4 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition duration-150 flex items-center justify-center space-x-1.5 shadow-sm" title="Export CSV">
                        <i class="ti ti-download text-sm"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">CSV</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Total Antrean -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Antrean</span>
                <span class="text-2xl font-extrabold text-slate-800 tracking-tight block">{{ number_format($totalQueues) }}</span>
                <span class="text-[9px] font-medium text-slate-400 block">Akumulasi tiket masuk</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0 border border-rose-100/50">
                <i class="ti ti-ticket text-xl"></i>
            </div>
        </div>

        <!-- Rata-rata Tunggu -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rata-rata Tunggu</span>
                <span class="text-2xl font-extrabold text-slate-800 tracking-tight block">
                    {{ floor($avgWaitTime / 60) }}<span class="text-xs font-semibold text-slate-400">m</span> {{ round($avgWaitTime % 60) }}<span class="text-xs font-semibold text-slate-400">s</span>
                </span>
                <span class="text-[9px] font-medium text-slate-400 block">Waktu respon loket</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 border border-blue-100/50">
                <i class="ti ti-hourglass-high text-xl"></i>
            </div>
        </div>

        <!-- Rata-rata Layanan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rata-rata Layanan</span>
                <span class="text-2xl font-extrabold text-slate-800 tracking-tight block">
                    {{ floor($avgServiceTime / 60) }}<span class="text-xs font-semibold text-slate-400">m</span> {{ round($avgServiceTime % 60) }}<span class="text-xs font-semibold text-slate-400">s</span>
                </span>
                <span class="text-[9px] font-medium text-slate-400 block">Durasi proses di operator</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-violet-50 text-violet-500 flex items-center justify-center flex-shrink-0 border border-violet-100/50">
                <i class="ti ti-clock-play text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3 pl-6">Waktu Masuk</th>
                        <th class="px-6 py-3">No. Antrian</th>
                        <th class="px-6 py-3">Divisi Layanan</th>
                        <th class="px-6 py-3">Loket Panggil</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right pr-6">Durasi Layanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($queues as $q)
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        <td class="px-6 py-3.5 pl-6 font-semibold text-slate-500">
                            <span class="text-slate-800 font-semibold">{{ $q->created_at->format('d M Y') }}</span>
                            <span class="block text-[9px] font-medium text-slate-400 mt-0.5">{{ $q->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="px-2.5 py-1 bg-slate-900 text-white text-[11px] font-bold rounded-lg shadow-sm font-mono">{{ $q->queue_number }}</span>
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-slate-700">{{ $q->serviceType->name }}</td>
                        <td class="px-6 py-3.5">
                            @if($q->counter)
                                <div class="font-semibold text-slate-700">{{ $q->counter->name }}</div>
                                <div class="text-[9px] font-medium text-slate-400 mt-0.5">{{ $q->counter->operator->name ?? '-' }}</div>
                            @else
                                <span class="text-slate-300 italic text-[11px]">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-center">
                            @php
                                $statusClasses = [
                                    'waiting' => 'bg-slate-100 text-slate-500 border-slate-250',
                                    'calling' => 'bg-amber-50 text-amber-600 border-amber-100/60',
                                    'serving' => 'bg-blue-50 text-blue-600 border-blue-100/60',
                                    'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100/60',
                                    'skipped' => 'bg-rose-50 text-rose-600 border-rose-100/60',
                                ];
                                $statusIcons = [
                                    'waiting' => 'ti ti-hourglass',
                                    'calling' => 'ti ti-volume',
                                    'serving' => 'ti ti-loader-2 animate-spin',
                                    'completed' => 'ti ti-circle-check',
                                    'skipped' => 'ti ti-circle-x',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider uppercase border {{ $statusClasses[$q->status] ?? 'bg-slate-100 text-slate-500' }}">
                                <i class="{{ $statusIcons[$q->status] ?? 'ti ti-info-circle' }} mr-1 text-xs"></i>
                                {{ $q->status }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 pr-6 text-right font-semibold text-slate-750">
                            @if($q->called_at && $q->completed_at)
                                @php $diff = $q->called_at->diffInSeconds($q->completed_at); @endphp
                                <span class="font-mono">{{ floor($diff / 60) }}m {{ $diff % 60 }}s</span>
                            @else
                                <span class="text-slate-300 italic text-[11px]">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-300 mb-3">
                                    <i class="ti ti-database-off text-2xl"></i>
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
            background: #f43f5e !important;
            border-color: #f43f5e !important;
            color: #ffffff !important;
        }
        .flatpickr-day.selected:hover {
            background: #e11d48 !important;
            border-color: #e11d48 !important;
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
