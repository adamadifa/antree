<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $institution->name ?? 'Antree' }} - Kiosk</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            background-image: radial-gradient(#94A3B8 0.5px, transparent 0.5px);
            background-size: 32px 32px;
            min-height: 100vh;
            overflow-x: hidden;
            color: #0F172A;
            /* Make dots very subtle */
            opacity: 0.99;
        }

        /* Modern Gradient Service Card */
        .service-card {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .service-card:active {
            transform: translateY(0);
        }

        /* Subtle Inner Glow */
        .service-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        /* Soft highlight flare (replaces AI floating circles) */
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

        /* Clean Modern Receipt */
        .receipt-card {
            background-color: white;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
            border: 1px solid #E2E8F0;
        }

        .printer-slot {
            height: 8px;
            background: #1E293B;
            border-radius: 9999px;
            width: 260px;
            margin: 0 auto -4px auto;
            position: relative;
            z-index: 20;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
        }

        .ticket-slide-down {
            animation: ticketSlideDown 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes ticketSlideDown {
            0% { transform: translateY(-80%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .fade-up {
            animation: fadeUp 0.6s ease-out both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased flex flex-col items-center justify-center p-6 lg:p-10 select-none">

    <!-- Unified Modern Header -->
    <header class="w-full max-w-6xl bg-white/90 backdrop-blur-md px-6 py-4 rounded-3xl border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between mb-10 fade-up gap-4">
        <!-- Left: Brand / Institution -->
        <div class="flex items-center gap-4">
            @if(isset($institution) && $institution->logo_path)
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1 border border-slate-100 flex-shrink-0">
                    <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
                </div>
            @else
                <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            @endif
            <div>
                <h1 class="text-lg font-bold text-slate-800 tracking-tight leading-tight">{{ $institution->name ?? 'Antree Kiosk' }}</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistem Aktif</span>
                </div>
            </div>
        </div>

        <!-- Right: Status & Clock Info -->
        <div class="flex items-center gap-6 ml-auto">
            {{-- Printer Status (Clean Pill Interaction) --}}
            <div id="printer-status-container" class="hidden md:flex items-center gap-2.5 cursor-pointer group" onclick="openPrinterModal()">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-100 group-hover:bg-slate-100 transition-colors">
                    <svg id="printer-icon" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </div>
                <div class="text-left">
                    <p id="printer-status-text" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Printer Off</p>
                    <p id="printer-name-text" class="text-[11px] font-bold text-slate-700 leading-none">Belum Terhubung</p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="h-6 w-px bg-slate-200 hidden md:block"></div>

            {{-- Clock (Clean Text Block) --}}
            <div class="text-right">
                <p id="current-time" class="text-xl font-extrabold text-slate-800 tabular-nums leading-none mb-1">00:00:00</p>
                <p id="current-date" class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none">Jum, 20 Mar 2026</p>
            </div>
        </div>
    </header>

    <!-- Title -->
    <div class="text-center mb-10 fade-up" style="animation-delay:0.05s">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 mb-3 tracking-tight">Silakan Pilih Layanan</h2>
        <p class="text-slate-500 font-medium text-sm lg:text-base">Sentuh pada salah satu kategori untuk mencetak nomor antrean Anda</p>
    </div>

    <!-- Service Grid -->
    <main class="w-full max-w-6xl fade-up" style="animation-delay:0.1s">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $index => $service)
            <button onclick="takeTicket({{ $service->id }}, '{{ $service->name }}')" 
                    class="service-card rounded-2xl text-left group overflow-hidden"
                    style="background: linear-gradient(135deg, {{ $service->color }}dd, {{ $service->color }}f2); animation-delay: {{ $index * 0.05 }}s">
                
                {{-- Clean Soft Highlight --}}
                <div class="card-flare"></div>

                <div class="relative z-10 flex items-center gap-5 px-7 py-6">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-white/20 backdrop-blur-sm flex-shrink-0 group-hover:bg-white/30 transition">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>

                    {{-- Text Content --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-extrabold text-white mb-0.5 leading-tight truncate">{{ $service->name }}</h3>
                        <p class="text-[10px] font-bold text-white/90 uppercase tracking-widest">KODE: {{ $service->code }}</p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex-shrink-0 text-right pl-4 border-l border-white/20">
                        <p class="text-[8px] font-black text-white/80 uppercase tracking-widest mb-0.5">TUNGGU</p>
                        <p class="text-lg font-extrabold text-white tabular-nums"><span id="service-wait-{{ $service->id }}">{{ $service->wait_time }}</span><span class="text-[9px] text-white/90 ml-0.5">m</span></p>
                    </div>

                    {{-- Arrow --}}
                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </button>
            @endforeach
        </div>
    </main>

    <!-- Footer: Live Status -->
    <footer class="mt-14 w-full max-w-6xl fade-up" style="animation-delay:0.15s">
        <div class="flex flex-col items-center">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-px w-10 bg-slate-300"></div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Antrean Sedang Dilayani</p>
                <div class="h-px w-10 bg-slate-300"></div>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($services as $service)
                <div class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm border border-slate-100">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $service->color }}"></span>
                    <span class="text-slate-500">{{ $service->code }}:</span>
                    <span id="service-queue-{{ $service->id }}" class="text-slate-800 font-extrabold">{{ $service->active_queue->queue_number ?? '-' }}</span>
                </div>
                @endforeach
            </div>
            <p class="mt-8 text-[11px] font-semibold text-slate-400">&copy; {{ date('Y') }} {{ $institution->footer_text ?? 'Antree - Sistem Antrean Mandiri' }}</p>
        </div>
    </footer>

    <!-- Print Modal -->
    <div id="print-modal" class="fixed inset-0 z-[100] hidden flex flex-col items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
        
        <div id="printing-state" class="text-center">
            <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-6"></div>
            <h3 class="text-2xl font-black text-white mb-2 tracking-tight drop-shadow-md">Mencetak Tiket...</h3>
            <p class="text-white/80 font-semibold text-xs drop-shadow-sm">Harap tunggu sebentar</p>
        </div>

        <div id="ticket-area" class="hidden flex flex-col items-center w-full">
            <div class="printer-slot"></div>
            <div id="actual-ticket" class="receipt-card p-8 text-left ticket-slide-down">
                
                <div class="text-center border-b-2 border-dashed border-slate-200 pb-5 mb-5">
                    @if(isset($institution) && $institution->logo_path)
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 p-1 border border-slate-100 shadow-sm">
                            <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    @endif
                    <h4 class="text-sm font-extrabold text-slate-800 mb-0.5">{{ $institution->name ?? 'Layanan Publik' }}</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tiket Antrean Resmi</p>
                </div>

                <div class="text-center mb-6">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nomor Antrean Anda</p>
                    <h5 id="res-number" class="text-5xl font-black text-slate-900 tracking-tight mb-2">A-001</h5>
                    <div class="inline-block px-4 py-1 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-lg" id="res-service-name">
                        Customer Service
                    </div>
                </div>

                <div class="space-y-2 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-500">Estimasi Tunggu</span>
                        <span class="font-extrabold text-slate-800" id="res-wait">~ 15 Menit</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-500">Waktu Cetak</span>
                        <span class="font-extrabold text-slate-800"><span id="res-date">20 Mar</span>, <span id="res-time">10:45</span></span>
                    </div>
                </div>

                <div class="text-center border-t-2 border-dashed border-slate-200 pt-5">
                    <p class="text-[10px] font-bold text-slate-500 mb-4">Silakan menunggu nomor Anda dipanggil.</p>
                    <!-- Simulated Barcode -->
                    <div class="flex justify-center flex-col items-center gap-1 opacity-60">
                        <div class="flex gap-0.5">
                            @for($i=0; $i<35; $i++)
                            <div class="w-[2px] bg-slate-800" style="height: {{ rand(15, 24) }}px;"></div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <button onclick="closeModal()" class="mt-8 px-10 py-3.5 bg-slate-900 text-white font-bold rounded-2xl shadow-xl hover:bg-slate-800 transition duration-200 text-sm w-full max-w-[320px]">
                Selesai
            </button>
        </div>
    </div>

    <!-- Printer Settings Modal -->
    <div id="printer-modal" class="fixed inset-0 z-[110] hidden flex flex-col items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-[2rem] p-8 w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Pengaturan Printer</h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Bluetooth Thermal</p>
                    </div>
                </div>
                <button onclick="closePrinterModal()" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-6">
                <div id="printer-connection-card" class="p-6 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center text-center">
                    <div id="printer-pulse" class="w-14 h-14 rounded-full bg-slate-200 flex items-center justify-center mb-4">
                        <svg id="printer-modal-icon" class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.343 6.343c5.857-5.858 15.355-5.858 21.213 0"/></svg>
                    </div>
                    <h4 id="printer-modal-status" class="text-base font-extrabold text-slate-800 mb-1">Printer Tidak Terhubung</h4>
                    <p id="printer-modal-desc" class="text-xs text-slate-500 mb-6 leading-relaxed">Hubungkan ke printer cetak thermal Bluetooth untuk mencetak tiket antrean secara otomatis.</p>
                    
                    <button id="btn-connect-printer" onclick="connectPrinter()" class="w-full py-3.5 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition duration-300 flex items-center justify-center gap-3 uppercase tracking-wider text-[10px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.343 6.343c5.857-5.858 15.355-5.858 21.213 0"/></svg>
                        Cari Printer Bluetooth
                    </button>

                    <button id="btn-disconnect-printer" class="hidden w-full py-3.5 bg-rose-50 text-rose-600 border border-rose-200 font-bold rounded-xl hover:bg-rose-100 transition duration-300 flex items-center justify-center gap-3 uppercase tracking-wider text-[10px]" onclick="disconnectPrinter()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Putuskan Koneksi
                    </button>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex gap-4 items-start">
                    <div class="w-7 h-7 rounded-lg bg-slate-200 flex-shrink-0 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase tracking-wider">Pastikan printer thermal dalam posisi menyala dan Bluetooth perangkat ini aktif.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripting Logic (Preserved 100%) -->
    <script>
        class BluetoothPrinter {
            constructor() {
                this.device = null;
                this.characteristic = null;
                this.connected = false;
                this.printerName = 'Belum Terhubung';
                this.onStatusChange = null;

                // ESC/POS Commands
                this.esc = {
                    init: [0x1B, 0x40],
                    center: [0x1B, 0x61, 0x01],
                    left: [0x1B, 0x61, 0x00],
                    boldOn: [0x1B, 0x45, 0x01],
                    boldOff: [0x1B, 0x45, 0x00],
                    doubleOn: [0x1B, 0x47, 0x01],
                    doubleOff: [0x1B, 0x47, 0x00],
                    doubleSize: [0x1D, 0x21, 0x11],
                    normalSize: [0x1D, 0x21, 0x00],
                    density: [0x12, 0x23, 0xFF],
                    feed: [0x0A],
                    cut: [0x1D, 0x56, 0x41]
                };
            }

            async getService(server) {
                try {
                    return await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                } catch (e) {
                    console.warn('Primary service lookup failed, forcing reconnect and trying fallback...', e);
                    // Explicitly reconnect to establish a fresh GATT server session
                    const freshServer = await this.device.gatt.connect();
                    const services = await freshServer.getPrimaryServices();
                    if (services.length === 0) {
                        throw new Error('No services found on this device');
                    }
                    return services[0];
                }
            }

            async connect() {
                try {
                    console.log('Requesting Bluetooth Device...');
                    this.device = await navigator.bluetooth.requestDevice({
                        filters: [
                            { services: ['000018f0-0000-1000-8000-00805f9b34fb'] },
                            { services: ['49535343-fe7d-4ae5-8fa9-9fafd205e455'] },
                            { namePrefix: 'TP' },
                            { namePrefix: 'RP' },
                            { namePrefix: 'RPP' },
                            { namePrefix: 'BlueTooth' },
                            { namePrefix: 'MPT' },
                            { namePrefix: 'Blueprint' }
                        ],
                        optionalServices: [
                            '000018f0-0000-1000-8000-00805f9b34fb', 
                            '0000ff00-0000-1000-8000-00805f9b34fb',
                            '00001101-0000-1000-8000-00805f9b34fb',
                            '0000e000-0000-1000-8000-00805f9b34fb',
                            '49535343-fe7d-4ae5-8fa9-9fafd205e455'
                        ]
                    });

                    this.printerName = this.device.name;
                    console.log('Connecting to GATT Server...');
                    let server = await this.device.gatt.connect();

                    // Wait for GATT session to stabilize (Windows needs this)
                    await new Promise(resolve => setTimeout(resolve, 1000));

                    // Retry service discovery up to 3 times
                    let service = null;
                    for (let attempt = 1; attempt <= 3; attempt++) {
                        try {
                            console.log(`Getting Service... (attempt ${attempt})`);
                            if (!this.device.gatt.connected) {
                                console.log('GATT dropped, reconnecting...');
                                server = await this.device.gatt.connect();
                                await new Promise(resolve => setTimeout(resolve, 800));
                            }
                            service = await this.getService(server);
                            break;
                        } catch (retryErr) {
                            console.warn(`Service discovery attempt ${attempt} failed:`, retryErr);
                            if (attempt === 3) throw retryErr;
                            await new Promise(resolve => setTimeout(resolve, 1000));
                        }
                    }

                    console.log('Getting Characteristic...');
                    const characteristics = await service.getCharacteristics();
                    this.characteristic = characteristics.find(c => c.properties.write || c.properties.writeWithoutResponse);

                    if (!this.characteristic) {
                        throw new Error('Could not find write characteristic');
                    }

                    this.connected = true;
                    this.device.addEventListener('gattserverdisconnected', () => this.handleDisconnect());
                    
                    if (this.onStatusChange) this.onStatusChange(true);
                    
                    localStorage.setItem('antree_printer_auto_connect', 'true');
                    
                    return true;
                } catch (error) {
                    console.error('Connection failed:', error);
                    return false;
                }
            }

            async autoConnect() {
                console.log('--- Checking Auto-Connect ---');
                if (localStorage.getItem('antree_printer_auto_connect') !== 'true') {
                    return false;
                }
                
                if (!navigator.bluetooth || !navigator.bluetooth.getDevices) {
                    return false;
                }

                try {
                    const devices = await navigator.bluetooth.getDevices();
                    if (devices.length > 0) {
                        const device = devices[0];
                        
                        this.device = device;
                        this.printerName = device.name;
                        
                        const server = await device.gatt.connect();
                        const service = await this.getService(server);

                        const characteristics = await service.getCharacteristics();
                        this.characteristic = characteristics.find(c => c.properties.write || c.properties.writeWithoutResponse);

                        if (this.characteristic) {
                            this.connected = true;
                            this.device.addEventListener('gattserverdisconnected', () => this.handleDisconnect());
                            if (this.onStatusChange) this.onStatusChange(true);
                            return true;
                        }
                    }
                } catch (error) {
                    console.error('Auto-connect error:', error);
                }
                return false;
            }

            handleDisconnect() {
                this.connected = false;
                this.characteristic = null;
                this.printerName = 'Belum Terhubung';
                if (this.onStatusChange) this.onStatusChange(false);
            }

            async disconnect() {
                localStorage.removeItem('antree_printer_auto_connect');
                if (this.device && this.device.gatt.connected) {
                    await this.device.gatt.disconnect();
                }
            }

            async printTicket(data) {
                if (!this.connected || !this.characteristic) return false;

                try {
                    const encoder = new TextEncoder();
                    let commands = [];

                    commands.push(...this.esc.init);
                    commands.push(...this.esc.density);
                    commands.push(...this.esc.doubleOn);
                    commands.push(...this.esc.center);
                    
                    commands.push(...this.esc.boldOn);
                    commands.push(...encoder.encode(data.institution_name.toUpperCase() + "\n"));
                    commands.push(...this.esc.boldOff);
                    commands.push(...encoder.encode("TIKET ANTREAN RESMI\n\n"));

                    commands.push(...encoder.encode("Nomor Antrean\n\n"));
                    commands.push(...this.esc.doubleSize);
                    commands.push(...this.esc.boldOn);
                    commands.push(...encoder.encode(data.queue_number + "\n\n"));
                    commands.push(...this.esc.boldOff);
                    commands.push(...this.esc.doubleOn);
                    commands.push(...this.esc.normalSize);

                    commands.push(...this.esc.boldOn);
                    commands.push(...encoder.encode(data.service_name.toUpperCase() + "\n\n"));
                    commands.push(...this.esc.boldOff);

                    commands.push(...this.esc.left);
                    commands.push(...encoder.encode(`Estimasi: ~ ${data.wait_time} MENIT\n`));
                    commands.push(...encoder.encode(`Waktu: ${data.date}, ${data.time}\n`));
                    commands.push(...encoder.encode("--------------------------------\n"));
                    
                    commands.push(...this.esc.center);
                    commands.push(...encoder.encode("Silakan menunggu nomor Anda\ndipanggil.\n\n\n\n"));
                    commands.push(...this.esc.cut);

                    const buffer = new Uint8Array(commands);
                    const chunkSize = 512;
                    for (let i = 0; i < buffer.length; i += chunkSize) {
                        const chunk = buffer.slice(i, i + chunkSize);
                        await this.characteristic.writeValue(chunk);
                    }

                    return true;
                } catch (error) {
                    console.error('Printing failed:', error);
                    return false;
                }
            }
        }

        const printer = new BluetoothPrinter();

        printer.onStatusChange = (isConnected) => {
            const container = document.getElementById('printer-status-container');
            const statusText = document.getElementById('printer-status-text');
            const nameText = document.getElementById('printer-name-text');
            
            const modalStatus = document.getElementById('printer-modal-status');
            const modalDesc = document.getElementById('printer-modal-desc');
            const modalIcon = document.getElementById('printer-modal-icon');
            const pulse = document.getElementById('printer-pulse');
            const btnConnect = document.getElementById('btn-connect-printer');
            const btnDisconnect = document.getElementById('btn-disconnect-printer');

            if (isConnected) {
                container.classList.remove('hidden');
                statusText.innerText = 'Printer Online';
                statusText.classList.replace('text-slate-500', 'text-emerald-500');
                nameText.innerText = printer.printerName;

                modalStatus.innerText = 'Printer Terhubung';
                modalDesc.innerText = `Terhubung ke ${printer.printerName}. Tiket akan dicetak secara otomatis.`;
                modalIcon.classList.replace('text-slate-400', 'text-emerald-500');
                pulse.classList.replace('bg-slate-200', 'bg-emerald-50');
                
                btnConnect.classList.add('hidden');
                btnDisconnect.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                statusText.innerText = 'Printer Off';
                statusText.classList.replace('text-emerald-500', 'text-slate-500');
                nameText.innerText = 'Belum Terhubung';

                modalStatus.innerText = 'Printer Tidak Terhubung';
                modalDesc.innerText = 'Hubungkan ke printer cetak thermal Bluetooth untuk mencetak tiket antrean secara otomatis.';
                modalIcon.classList.replace('text-emerald-500', 'text-slate-400');
                pulse.classList.replace('bg-emerald-50', 'bg-slate-200');

                btnConnect.classList.remove('hidden');
                btnDisconnect.classList.add('hidden');
            }
        };

        window.addEventListener('load', () => {
            let attempts = 0;
            const checkBluetooth = async () => {
                const connected = await printer.autoConnect();
                if (!connected && attempts < 3) {
                    attempts++;
                    setTimeout(checkBluetooth, 1500);
                }
            };
            setTimeout(checkBluetooth, 1000);
        });

        function openPrinterModal() {
            document.getElementById('printer-modal').classList.remove('hidden');
        }

        function closePrinterModal() {
            document.getElementById('printer-modal').classList.add('hidden');
        }

        async function connectPrinter() {
            const btn = document.getElementById('btn-connect-printer');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin"></span> Memindai...';

            const success = await printer.connect();
            
            btn.disabled = false;
            btn.innerHTML = originalText;
            
            if (success) {
                setTimeout(closePrinterModal, 1000);
            } else {
                alert('Gagal terhubung. Pastikan Bluetooth aktif dan printer siap.');
            }
        }

        function disconnectPrinter() {
            if (confirm('Putuskan koneksi dari printer?')) {
                printer.disconnect();
            }
        }

        function updateClock() {
            const now = new Date();
            document.getElementById('current-time').innerText = now.toLocaleTimeString('id-ID', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('current-date').innerText = now.toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        function takeTicket(serviceId, serviceName) {
            const modal = document.getElementById('print-modal');
            const printingState = document.getElementById('printing-state');
            const ticketArea = document.getElementById('ticket-area');

            modal.classList.remove('hidden');
            printingState.classList.remove('hidden');
            ticketArea.classList.add('hidden');

            fetch("{{ route('kiosk.take-ticket') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ service_type_id: serviceId })
            })
            .then(r => r.json())
            .then(async data => {
                if (data.success) {
                    if (printer.connected) {
                        await printer.printTicket({
                            ...data.data,
                            institution_name: "{{ $institution->name ?? 'Antree' }}"
                        });
                    }

                    setTimeout(() => {
                        printingState.classList.add('hidden');
                        ticketArea.classList.remove('hidden');
                        document.getElementById('res-number').innerText = data.data.queue_number;
                        document.getElementById('res-service-name').innerText = data.data.service_name;
                        document.getElementById('res-date').innerText = data.data.date;
                        document.getElementById('res-time').innerText = data.data.time;
                        document.getElementById('res-wait').innerText = "~ " + data.data.wait_time + " Menit";

                        const footerQueue = document.getElementById(`service-queue-${serviceId}`);
                        if (footerQueue) footerQueue.innerText = data.data.queue_number;
                        
                        const cardWait = document.getElementById(`service-wait-${serviceId}`);
                        if (cardWait) cardWait.innerText = data.data.wait_time;
                    }, 1200);
                }
            })
            .catch((err) => {
                console.error(err);
                alert("Terjadi kesalahan jaringan.");
                modal.classList.add('hidden');
            });
        }

        function closeModal() {
            document.getElementById('print-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
