@extends('layouts.admin')

@section('title', 'Queue Management')
@section('header', 'Queue Management')

@section('content')
    {{-- Welcome Banner --}}
    <div class="bg-slate-900 rounded-2xl p-8 mb-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-[10px] font-bold text-teal-400 uppercase tracking-wider">Live Overview</span>
            <h1 class="text-2xl font-bold tracking-tight text-white mt-1">
                @php
                    $hour = date('H');
                    $greeting = 'Selamat Datang';
                    if ($hour < 11) $greeting = 'Selamat Pagi';
                    elseif ($hour < 15) $greeting = 'Selamat Siang';
                    elseif ($hour < 19) $greeting = 'Selamat Sore';
                    else $greeting = 'Selamat Malam';
                @endphp
                {{ $greeting }}, {{ Auth::user()->name }}
            </h1>
            <p class="text-slate-400 text-xs mt-1.5">
                Sistem berjalan dengan optimal. Anda sedang mengelola antrean untuk <span class="text-slate-200 font-semibold">{{ $institution->name ?? 'Instansi Anda' }}</span> hari ini.
            </p>
        </div>
        <div class="flex items-center gap-3 bg-slate-800 border border-slate-700/60 rounded-xl px-4 py-2.5 self-start md:self-auto">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <div class="text-xs">
                <span class="text-slate-400 block font-medium text-[10px] uppercase tracking-wider">Hari ini</span>
                <span class="text-white font-bold">{{ date('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Row 1: Statistic Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        {{-- Total Antrian --}}
        <div class="bg-teal-600 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between text-white">
            <div>
                <span class="text-[11px] font-bold text-teal-100 uppercase tracking-wider block mb-1">Total Antrian</span>
                <span class="text-3xl font-extrabold text-white tracking-tight block">{{ $totalToday }}</span>
                <span class="text-[10px] font-semibold text-teal-100/90 block mt-1">Antrean masuk hari ini</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-500/40 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
        </div>

        {{-- Sedang Dilayani --}}
        <div class="bg-blue-600 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between text-white">
            <div>
                <span class="text-[11px] font-bold text-blue-100 uppercase tracking-wider block mb-1">Sedang Dilayani</span>
                <span class="text-3xl font-extrabold text-white tracking-tight block">{{ $servingNow }}</span>
                <span class="text-[10px] font-semibold text-blue-100/90 block mt-1">Aktif dilayani diloket</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-500/40 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="bg-emerald-600 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between text-white">
            <div>
                <span class="text-[11px] font-bold text-emerald-100 uppercase tracking-wider block mb-1">Selesai Dilayani</span>
                <span class="text-3xl font-extrabold text-white tracking-tight block">{{ $completedToday }}</span>
                <span class="text-[10px] font-semibold text-emerald-100/90 block mt-1">Total selesai diproses</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/40 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Rata-rata Waktu --}}
        <div class="bg-orange-600 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between text-white">
            <div>
                <span class="text-[11px] font-bold text-orange-100 uppercase tracking-wider block mb-1">Rata-rata Waktu</span>
                <span class="text-3xl font-extrabold text-white tracking-tight block">{{ $avgServiceTime }}</span>
                <span class="text-[10px] font-semibold text-orange-100/90 block mt-1">Durasi per layanan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-500/40 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Row 2: Chart + Service Stats --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        {{-- Grafik Kunjungan --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Grafik Pengunjung</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Statistik data kunjungan 7 hari terakhir</p>
                </div>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg">Harian</span>
            </div>

            {{-- SVG Area Chart --}}
            <div class="relative" style="height: 220px;">
                @if($chartData->count() > 0)
                @php
                    $maxCount = max($chartData->max(), 1);
                    $chartHeight = 150;
                    $chartWidth = 600;
                    $points = [];
                    $dates = $chartData->keys();
                    $counts = $chartData->values();
                    $stepX = $chartWidth / (max(count($dates) - 1, 1));
                    for($i=0; $i<count($dates); $i++) {
                        $x = $i * $stepX;
                        $y = $chartHeight - ($counts[$i] / $maxCount * $chartHeight);
                        $points[] = "$x,$y";
                    }
                    $pathData = "M" . implode(" L", $points);
                    $areaData = $pathData . " L" . ($chartWidth) . ",$chartHeight L0,$chartHeight Z";
                @endphp
                <svg class="w-full h-full" viewBox="0 0 600 160" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="chartGrad" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#0d9488" stop-opacity="0.1" />
                            <stop offset="100%" stop-color="#0d9488" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    
                    {{-- Grid Lines --}}
                    @for($g = 0; $g <= 4; $g++)
                    @php $gridY = ($chartHeight / 4) * $g; @endphp
                    <line x1="0" y1="{{ $gridY }}" x2="600" y2="{{ $gridY }}" stroke="#f1f5f9" stroke-width="1.2" stroke-dasharray="3" />
                    @endfor
                    
                    {{-- Area --}}
                    <path d="{{ $areaData }}" fill="url(#chartGrad)" />
                    {{-- Line --}}
                    <path d="{{ $pathData }}" fill="none" stroke="#0d9488" stroke-width="2.5" stroke-linecap="round" />
                    {{-- Data points --}}
                    @foreach($points as $index => $p)
                    @php $coord = explode(',', $p); @endphp
                    <circle cx="{{ $coord[0] }}" cy="{{ $coord[1] }}" r="4" fill="#0d9488" stroke="white" stroke-width="2"/>
                    @endforeach
                </svg>
                <div class="flex justify-between mt-3 px-1">
                    @foreach($chartData as $date => $count)
                    <span class="text-[10px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($date)->format('d M') }}</span>
                    @endforeach
                </div>
                @else
                <div class="h-full flex items-center justify-center text-slate-400 text-xs italic">
                    Belum ada data kunjungan.
                </div>
                @endif
            </div>
        </div>

        {{-- Statistik Layanan --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Statistik Layanan</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Persentase kontribusi antrean per divisi</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($serviceStats as $stat)
                    <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl transition duration-150">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-700">{{ $stat->name }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-slate-900">{{ $stat->queues_count }}</span>
                                <span class="text-[10px] font-bold text-slate-400 bg-white border border-slate-200/60 px-1.5 py-0.5 rounded">{{ $stat->percentage }}%</span>
                            </div>
                        </div>
                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500" style="width: {{ $stat->percentage }}%; background-color: {{ $stat->color ?? '#0d9488' }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Loket Aktif --}}
    <h3 class="text-sm font-bold text-slate-800 mb-4 px-1">Monitoring Loket Terkini</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        @foreach($counters as $counter)
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase
                        {{ $counter->status === 'online' ? 'text-emerald-700 bg-emerald-50 border border-emerald-100' : ($counter->status === 'break' ? 'text-amber-700 bg-amber-50 border border-amber-100' : 'text-slate-500 bg-slate-50 border border-slate-200') }}">
                        @if($counter->status === 'online')
                        <span class="relative flex h-1.5 w-1.5 mr-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                        </span>
                        @else
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $counter->status === 'break' ? 'bg-amber-400' : 'bg-slate-300' }}"></span>
                        @endif
                        {{ $counter->status }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400">ID: #{{ $counter->id }}</span>
                </div>
                
                <h4 class="text-sm font-bold text-slate-850 leading-tight">{{ $counter->name }}</h4>
                <p class="text-[10px] font-medium text-slate-400 mt-1 uppercase tracking-wider">{{ $counter->serviceType->name }}</p>
            </div>
            
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Operator</p>
                    <p class="text-xs font-bold text-slate-700 truncate max-w-[95px] mt-0.5">{{ $counter->operator->name ?? '—' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Serving</p>
                    <span class="inline-block mt-0.5 text-xs font-extrabold px-2 py-0.5 bg-slate-900 text-white rounded">{{ $counter->current_queue->queue_number ?? '—' }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Row 4: Table + Activity --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Antrian Berjalan Table --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Antrian Berjalan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Daftar aktivitas antrean yang terdaftar hari ini</p>
                </div>
                <span class="px-2.5 py-1 bg-slate-50 border border-slate-200 text-slate-500 text-[10px] font-bold rounded-lg uppercase tracking-wider">Terbaru</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-150">
                            <th class="pb-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider pl-1">No. Antrian</th>
                            <th class="pb-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Layanan</th>
                            <th class="pb-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Loket</th>
                            <th class="pb-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                            <th class="pb-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider pr-1 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentQueues as $q)
                        <tr class="hover:bg-slate-50/50 transition duration-150 group">
                            <td class="py-3.5 pl-1">
                                <div class="flex items-center space-x-3">
                                    <span class="w-8 h-8 rounded-lg bg-teal-50 border border-teal-100 flex items-center justify-center text-xs font-bold text-teal-700 shadow-sm">{{ $q->queue_number }}</span>
                                    <div>
                                        <span class="text-xs font-bold text-slate-800">{{ $q->customer_name ?? 'Pelanggan' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5">
                                <span class="text-xs font-bold text-slate-700">{{ $q->serviceType->name }}</span>
                            </td>
                            <td class="py-3.5">
                                <span class="text-xs font-medium text-slate-600">{{ $q->counter->name ?? '—' }}</span>
                            </td>
                            <td class="py-3.5">
                                <span class="text-[11px] font-semibold text-slate-400">{{ $q->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-3.5 pr-1 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase
                                    {{ $q->status === 'waiting' ? 'bg-slate-50 border border-slate-200 text-slate-500' : 
                                       ($q->status === 'completed' ? 'bg-emerald-50 border border-emerald-100 text-emerald-700' : 'bg-teal-50 border border-teal-100 text-teal-700') }}">
                                    <span class="w-1 h-1 rounded-full mr-1.5 
                                        {{ $q->status === 'waiting' ? 'bg-slate-400' : 
                                           ($q->status === 'completed' ? 'bg-emerald-500' : 'bg-teal-500') }}"></span>
                                    {{ $q->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs italic">
                                Belum ada antrean yang terdaftar hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Aktivitas Hari Ini --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Aktivitas Hari Ini</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Timeline log panggilan hari ini</p>
                </div>
                <span class="text-[10px] font-bold text-teal-600 bg-teal-50 border border-teal-100 px-2.5 py-1 rounded-lg uppercase tracking-wider">{{ date('d M Y') }}</span>
            </div>

            <div class="relative pl-6">
                {{-- Timeline line --}}
                <div class="absolute left-[9px] top-2 bottom-2 w-px bg-slate-100"></div>

                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                    <div class="relative group">
                        <div class="absolute -left-6 top-1.5 w-[12px] h-[12px] rounded-full bg-white border-2 border-teal-500 shadow-sm"></div>
                        <div class="bg-slate-50 hover:bg-teal-50 border border-slate-100 hover:border-teal-100 rounded-xl p-3.5 transition duration-150">
                            <div class="flex justify-between items-center mb-1">
                                <span class="inline-block text-[9px] font-bold px-1.5 py-0.5 bg-white text-teal-600 rounded border border-slate-100">{{ $activity->called_at->format('H:i') }} WIB</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Call Log</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-800">No. {{ $activity->queue_number }} Dipanggil</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $activity->counter->name ?? 'Loket' }} — {{ $activity->serviceType->name }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-xs text-slate-400 italic">Belum ada aktivitas panggilan hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

