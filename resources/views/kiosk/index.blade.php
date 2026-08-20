<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Antree - Kiosk</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F1F5F9;
            background-image: radial-gradient(#CBD5E1 0.6px, transparent 0.6px);
            background-size: 28px 28px;
            min-height: 100vh;
            overflow: hidden;
            color: #0F172A;
        }

        .service-card {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
        }

        .service-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .service-card:active {
            transform: translateY(-2px) scale(0.98);
        }

        /* Ornament: Large circle in top-right */
        .service-card .ornament-circle {
            position: absolute;
            top: -40px;
            right: -40px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            transition: transform 0.5s ease;
        }
        .service-card:hover .ornament-circle {
            transform: scale(1.3);
        }

        /* Ornament: Small circle bottom-left */
        .service-card .ornament-circle-sm {
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

        /* Ornament: Diagonal line pattern */
        .service-card .ornament-lines {
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background-image: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 20px,
                rgba(255,255,255,0.03) 20px,
                rgba(255,255,255,0.03) 21px
            );
            pointer-events: none;
        }

        /* Thermal Ticket */
        .thermal-ticket {
            background-color: white;
            position: relative;
            filter: drop-shadow(0 25px 50px rgba(0,0,0,0.3));
            width: 300px;
            margin: 0 auto;
        }
        .thermal-ticket::after {
            content: "";
            position: absolute;
            bottom: -12px; left: 0; right: 0;
            height: 12px;
            background-repeat: repeat-x;
            background-size: 24px 12px;
            background-image: radial-gradient(circle at 12px 18px, transparent 15px, white 16px);
            z-index: 10;
        }
        .printer-slot {
            height: 12px;
            background: #020617;
            border-radius: 6px;
            width: 320px;
            margin: 0 auto -6px auto;
            position: relative;
            z-index: 20;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.9);
        }
        .ticket-slide-down {
            animation: ticketSlideDown 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes ticketSlideDown {
            0% { transform: translateY(-110%); opacity: 0; }
            5% { opacity: 1; }
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
<body class="antialiased flex flex-col items-center justify-center p-8 lg:p-12">

    <!-- Header -->
    <header class="w-full max-w-6xl flex items-center justify-between mb-14 fade-up">
        <div class="flex items-center gap-5">
            @if(isset($institution) && $institution->logo_path)
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg p-1.5 border border-slate-100">
                    <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
                </div>
            @else
                <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $institution->name ?? 'Antree Kiosk' }}</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">System Online</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            {{-- Printer Status --}}
            <div id="printer-status-container" class="hidden md:flex items-center gap-2.5 bg-white/60 backdrop-blur-sm px-4 py-2.5 rounded-xl border border-white shadow-sm cursor-pointer hover:bg-white/80 transition-all group" onclick="openPrinterModal()">
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-teal-50 transition-colors">
                    <svg id="printer-icon" class="w-4 h-4 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </div>
                <div class="text-left">
                    <p id="printer-status-text" class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-0.5">Printer Offline</p>
                    <p id="printer-name-text" class="text-[10px] font-bold text-slate-800 leading-none">Not Connected</p>
                </div>
                <div id="printer-dot" class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
            </div>

            <div class="text-right bg-white/60 backdrop-blur-sm px-5 py-2.5 rounded-xl border border-white shadow-sm">
                <p id="current-time" class="text-xl font-extrabold text-slate-800 tabular-nums">00:00:00</p>
                <p id="current-date" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Fri, Mar 20, 2026</p>
            </div>
        </div>
    </header>

    <!-- Title -->
    <div class="text-center mb-10 fade-up" style="animation-delay:0.05s">
        <h2 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Select Your Service</h2>
        <p class="text-slate-400 font-medium text-sm">Tap a service category to get your queue ticket</p>
    </div>

    <!-- Service Grid: 3 columns, compact height, colored BG -->
    <main class="w-full max-w-6xl fade-up" style="animation-delay:0.1s">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($services as $index => $service)
            <button onclick="takeTicket({{ $service->id }}, '{{ $service->name }}')" 
                    class="service-card rounded-2xl text-left group"
                    style="background: linear-gradient(135deg, {{ $service->color }}E6, {{ $service->color }}CC); animation-delay: {{ $index * 0.05 }}s">
                
                {{-- Ornaments --}}
                <div class="ornament-circle"></div>
                <div class="ornament-circle-sm"></div>
                <div class="ornament-lines"></div>

                <div class="relative z-10 flex items-center gap-5 px-7 py-6">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-white/20 backdrop-blur-sm flex-shrink-0 group-hover:bg-white/30 transition">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>

                    {{-- Text Content --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-extrabold text-white mb-0.5 leading-tight truncate">{{ $service->name }}</h3>
                        <p class="text-[10px] font-bold text-white uppercase tracking-widest">Code: {{ $service->code }}</p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex-shrink-0 text-right pl-4 border-l border-white/30">
                        <p class="text-[8px] font-black text-white uppercase tracking-widest mb-0.5">Wait</p>
                        <p class="text-lg font-extrabold text-white tabular-nums"><span id="service-wait-{{ $service->id }}">{{ $service->wait_time }}</span><span class="text-[9px] text-white ml-0.5">m</span></p>
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
            <div class="flex items-center gap-3 mb-4">
                <div class="h-px w-10 bg-slate-200"></div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Live Queue Status</p>
                <div class="h-px w-10 bg-slate-200"></div>
            </div>
            <div class="flex flex-wrap justify-center gap-2.5">
                @foreach($services as $service)
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg text-[9px] font-bold shadow-sm border border-slate-100">
                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $service->color }}"></span>
                    <span class="text-slate-500">{{ $service->code }}:</span>
                    <span id="service-queue-{{ $service->id }}" class="text-slate-900 font-extrabold">{{ $service->active_queue->queue_number ?? '-' }}</span>
                </div>
                @endforeach
            </div>
            <p class="mt-6 text-[10px] font-semibold text-slate-400">&copy; {{ date('Y') }} {{ $institution->footer_text ?? 'Antree Queue Management' }}</p>
        </div>
    </footer>

    <!-- Print Modal -->
    <div id="print-modal" class="fixed inset-0 z-[100] hidden flex flex-col items-center justify-center p-8 bg-slate-900/90 backdrop-blur-md transition-all duration-500">
        
        <div id="printing-state" class="text-center">
            <div class="w-20 h-20 border-4 border-teal-500/10 border-t-teal-500 rounded-full animate-spin mx-auto mb-8"></div>
            <h3 class="text-3xl font-black text-white mb-2 tracking-tight">Printing Your Ticket</h3>
            <p class="text-teal-400 font-bold text-[10px]">Processing...</p>
        </div>

        <div id="ticket-area" class="hidden flex flex-col items-center w-full">
            <div class="printer-slot"></div>
            <div id="actual-ticket" class="thermal-ticket p-10 text-left ticket-slide-down">
                <div class="text-center border-b-2 border-dashed border-slate-100 pb-6 mb-6">
                    @if(isset($institution) && $institution->logo_path)
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mx-auto mb-3 p-1 border border-slate-100 shadow-sm">
                            <img src="{{ asset($institution->logo_path) }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    @endif
                    <h4 class="text-[13px] font-bold text-slate-900 mb-0.5">{{ $institution->name ?? 'Public Service' }}</h4>
                    <p class="text-[10px] font-semibold text-slate-400">Official Queue Ticket</p>
                </div>

                <div class="text-center mb-8">
                    <p class="text-[10px] font-bold text-slate-500 mb-2">Queue Number</p>
                    <h5 id="res-number" class="text-6xl font-black text-slate-900 tracking-tighter mb-2">A-001</h5>
                    <div class="inline-block px-3.5 py-1 bg-slate-900 text-white text-[10px] font-bold rounded-full" id="res-service-name">
                        Customer Service
                    </div>
                </div>

                <div class="space-y-2.5 mb-8 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-semibold text-slate-500">Wait</span>
                        <span class="text-[10.5px] font-bold text-slate-800" id="res-wait">~ 15 Mins</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-semibold text-slate-500">Issued</span>
                        <span class="text-[10.5px] font-bold text-slate-800"><span id="res-date">Mar 20</span>, <span id="res-time">10:45</span></span>
                    </div>
                </div>

                <div class="text-center border-t-2 border-dashed border-slate-100 pt-5">
                    <p class="text-[9px] font-medium text-slate-500 mb-4 italic">Please wait for your number to be called.</p>
                    <div class="flex justify-center flex-col items-center gap-1 opacity-50">
                        <div class="flex gap-0.5">
                            @for($i=0; $i<30; $i++)
                            <div class="w-0.5 bg-slate-900" style="height: {{ rand(15, 28) }}px;"></div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <button onclick="closeModal()" class="mt-14 px-10 py-3.5 bg-teal-500 text-white font-bold rounded-2xl shadow-xl shadow-teal-500/20 hover:bg-teal-600 transition duration-200 text-sm">
                Done
            </button>
        </div>
    </div>

    <!-- Printer Settings Modal -->
    <div id="printer-modal" class="fixed inset-0 z-[110] hidden flex flex-col items-center justify-center p-8 bg-slate-900/90 backdrop-blur-md transition-all duration-500">
        <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Printer Settings</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Bluetooth Thermal Printer</p>
                    </div>
                </div>
                <button onclick="closePrinterModal()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-6">
                {{-- Connection Status Card --}}
                <div id="printer-connection-card" class="p-6 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center text-center">
                    <div id="printer-pulse" class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center mb-4">
                        <svg id="printer-modal-icon" class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.343 6.343c5.857-5.858 15.355-5.858 21.213 0"/></svg>
                    </div>
                    <h4 id="printer-modal-status" class="text-lg font-extrabold text-slate-900 mb-1">No Printer Connected</h4>
                    <p id="printer-modal-desc" class="text-sm text-slate-500 mb-6">Connect to a Bluetooth thermal printer to enable automatic ticket printing.</p>
                    
                    <button id="btn-connect-printer" onclick="connectPrinter()" class="w-full py-4 bg-slate-900 text-white font-black rounded-xl shadow-xl hover:bg-slate-800 transition duration-300 flex items-center justify-center gap-3 uppercase tracking-widest text-[10px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.343 6.343c5.857-5.858 15.355-5.858 21.213 0"/></svg>
                        Scan For Printer
                    </button>

                    <button id="btn-disconnect-printer" onclick="disconnectPrinter()" class="hidden w-full py-4 bg-white text-rose-500 border-2 border-rose-100 font-black rounded-xl hover:bg-rose-50 transition duration-300 flex items-center justify-center gap-3 uppercase tracking-widest text-[10px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Disconnect Printer
                    </button>
                </div>

                <div class="p-4 rounded-xl bg-teal-50 border border-teal-100 flex gap-4">
                    <div class="w-8 h-8 rounded-lg bg-teal-500 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-teal-700 leading-relaxed uppercase tracking-wider">Ensure your thermal printer is turned on and Bluetooth is discoverable on this device.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Bluetooth Printer Implementation ---
        class BluetoothPrinter {
            constructor() {
                this.device = null;
                this.characteristic = null;
                this.connected = false;
                this.printerName = 'Not Connected';
                this.onStatusChange = null;

                // ESC/POS Commands
                this.esc = {
                    init: [0x1B, 0x40],
                    center: [0x1B, 0x61, 0x01],
                    left: [0x1B, 0x61, 0x00],
                    boldOn: [0x1B, 0x45, 0x01],
                    boldOff: [0x1B, 0x45, 0x00],
                    doubleOn: [0x1B, 0x47, 0x01], // Double print (Emphasis)
                    doubleOff: [0x1B, 0x47, 0x00],
                    doubleSize: [0x1D, 0x21, 0x11],
                    normalSize: [0x1D, 0x21, 0x00],
                    density: [0x12, 0x23, 0xFF], // Max density (standard on many printers)
                    feed: [0x0A],
                    cut: [0x1D, 0x56, 0x41]
                };
            }

            async connect() {
                try {
                    console.log('Requesting Bluetooth Device...');
                    this.device = await navigator.bluetooth.requestDevice({
                        filters: [
                            { services: ['000018f0-0000-1000-8000-00805f9b34fb'] },
                            { services: ['49535343-fe7d-4ae5-8fa9-9fafd205e455'] },
                            { namePrefix: 'TP' },
                            { namePrefix: 'RPP' },
                            { namePrefix: 'BlueTooth' },
                            { namePrefix: 'MPT' }
                        ],
                        optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb', '0000ff00-0000-1000-8000-00805f9b34fb']
                    });

                    this.printerName = this.device.name;
                    console.log('Connecting to GATT Server...');
                    const server = await this.device.gatt.connect();

                    console.log('Getting Service...');
                    // Try common printer services
                    let service;
                    try {
                        service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                    } catch (e) {
                        const services = await server.getPrimaryServices();
                        service = services[0];
                    }

                    console.log('Getting Characteristic...');
                    const characteristics = await service.getCharacteristics();
                    // Find the first characteristic that supports writing
                    this.characteristic = characteristics.find(c => c.properties.write || c.properties.writeWithoutResponse);

                    if (!this.characteristic) {
                        throw new Error('Could not find write characteristic');
                    }

                    this.connected = true;
                    this.device.addEventListener('gattserverdisconnected', () => this.handleDisconnect());
                    
                    if (this.onStatusChange) this.onStatusChange(true);
                    
                    // Save to local storage for persistence
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
                    console.log('Auto-connect not enabled in settings.');
                    return false;
                }
                
                if (!navigator.bluetooth) {
                    console.error('Web Bluetooth not supported in this browser.');
                    return false;
                }

                if (!navigator.bluetooth.getDevices) {
                    console.warn('getDevices() not supported. Auto-connect might require a manual gesture.');
                    return false;
                }

                try {
                    const devices = await navigator.bluetooth.getDevices();
                    console.log('Available permitted devices:', devices.length);
                    
                    if (devices.length > 0) {
                        const device = devices[0];
                        console.log('Attempting to reconnect to:', device.name);
                        
                        this.device = device;
                        this.printerName = device.name;
                        
                        // Handle server connection
                        const server = await device.gatt.connect();
                        console.log('GATT Connected');
                        
                        let service;
                        try {
                            service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                        } catch (e) {
                            const services = await server.getPrimaryServices();
                            service = services[0];
                        }
                        console.log('Service found');

                        const characteristics = await service.getCharacteristics();
                        this.characteristic = characteristics.find(c => c.properties.write || c.properties.writeWithoutResponse);

                        if (this.characteristic) {
                            console.log('Characteristic found, reconnection SUCCESS');
                            this.connected = true;
                            this.device.addEventListener('gattserverdisconnected', () => this.handleDisconnect());
                            if (this.onStatusChange) this.onStatusChange(true);
                            return true;
                        }
                    } else {
                        console.log('No permitted devices found. You may need to connect manually once first.');
                    }
                } catch (error) {
                    console.error('Auto-connect error:', error);
                }
                return false;
            }

            handleDisconnect() {
                this.connected = false;
                this.characteristic = null;
                this.printerName = 'Not Connected';
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

                    // Start Building Buffer
                    commands.push(...this.esc.init);
                    commands.push(...this.esc.density); // Set higher density
                    commands.push(...this.esc.doubleOn); // Enable double strike for sharpness
                    commands.push(...this.esc.center);
                    
                    // Institution Name
                    commands.push(...this.esc.boldOn);
                    commands.push(...encoder.encode(data.institution_name.toUpperCase() + "\n"));
                    commands.push(...this.esc.boldOff);
                    commands.push(...encoder.encode("Official Queue Ticket\n\n"));

                    // Queue Number
                    commands.push(...encoder.encode("Queue Number\n\n"));
                    commands.push(...this.esc.doubleSize);
                    commands.push(...this.esc.boldOn);
                    commands.push(...encoder.encode(data.queue_number + "\n\n"));
                    commands.push(...this.esc.boldOff);
                    commands.push(...this.esc.doubleOn); // Re-enable if doubleSize affects it
                    commands.push(...this.esc.normalSize);

                    // Service Name
                    commands.push(...this.esc.boldOn);
                    commands.push(...encoder.encode(data.service_name.toUpperCase() + "\n\n"));
                    commands.push(...this.esc.boldOff);

                    // Details
                    commands.push(...this.esc.left);
                    commands.push(...encoder.encode(`Wait: ~ ${data.wait_time} MINS\n`));
                    commands.push(...encoder.encode(`Date: ${data.date}, ${data.time}\n`));
                    commands.push(...encoder.encode("--------------------------------\n"));
                    
                    // Footer
                    commands.push(...this.esc.center);
                    commands.push(...encoder.encode("Please wait for your number\nto be called.\n\n\n\n"));
                    commands.push(...this.esc.cut);

                    // Send in chunks of 512 bytes (common limit for some BT printers)
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

        // --- View Logic ---
        const printer = new BluetoothPrinter();

        printer.onStatusChange = (isConnected) => {
            const container = document.getElementById('printer-status-container');
            const statusText = document.getElementById('printer-status-text');
            const nameText = document.getElementById('printer-name-text');
            const dot = document.getElementById('printer-dot');
            const icon = document.getElementById('printer-icon');
            
            // Modal elements
            const modalStatus = document.getElementById('printer-modal-status');
            const modalDesc = document.getElementById('printer-modal-desc');
            const modalIcon = document.getElementById('printer-modal-icon');
            const pulse = document.getElementById('printer-pulse');
            const btnConnect = document.getElementById('btn-connect-printer');
            const btnDisconnect = document.getElementById('btn-disconnect-printer');

            if (isConnected) {
                container.classList.remove('hidden');
                statusText.innerText = 'Printer Online';
                statusText.classList.replace('text-slate-400', 'text-teal-500');
                nameText.innerText = printer.printerName;
                dot.classList.replace('bg-slate-300', 'bg-teal-500');
                dot.classList.add('animate-pulse');
                icon.classList.replace('text-slate-400', 'text-teal-500');

                modalStatus.innerText = 'Printer Connected';
                modalDesc.innerText = `Connected to ${printer.printerName}. Tickets will be printed automatically.`;
                modalIcon.classList.replace('text-slate-400', 'text-teal-500');
                pulse.classList.replace('bg-slate-200', 'bg-teal-50');
                
                btnConnect.classList.add('hidden');
                btnDisconnect.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                statusText.innerText = 'Printer Offline';
                statusText.classList.replace('text-teal-500', 'text-slate-400');
                nameText.innerText = 'Not Connected';
                dot.classList.replace('bg-teal-500', 'bg-slate-300');
                dot.classList.remove('animate-pulse');
                icon.classList.replace('text-teal-500', 'text-slate-400');

                modalStatus.innerText = 'No Printer Connected';
                modalDesc.innerText = 'Connect to a Bluetooth thermal printer to enable automatic ticket printing.';
                modalIcon.classList.replace('text-teal-500', 'text-slate-400');
                pulse.classList.replace('bg-teal-50', 'bg-slate-200');

                btnConnect.classList.remove('hidden');
                btnDisconnect.classList.add('hidden');
            }
        };

        // Auto-reconnect on page load
        window.addEventListener('load', () => {
            console.log('Kiosk Page Loaded, initializing printer check...');
            // Check every 500ms if Bluetooth is ready or try a few times
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
            btn.innerHTML = '<span class="w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin"></span> Scanning...';

            const success = await printer.connect();
            
            btn.disabled = false;
            btn.innerHTML = originalText;
            
            if (success) {
                setTimeout(closePrinterModal, 1000);
            } else {
                alert('Bluetooth connection failed. Make sure Bluetooth is enabled and the printer is discoverable.');
            }
        }

        function disconnectPrinter() {
            if (confirm('Disconnect from printer?')) {
                printer.disconnect();
            }
        }

        function updateClock() {
            const now = new Date();
            document.getElementById('current-time').innerText = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('current-date').innerText = now.toLocaleDateString('en-US', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' });
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
                    // Start physical printing if connected
                    if (printer.connected) {
                        const printSuccess = await printer.printTicket({
                            ...data.data,
                            institution_name: "{{ $institution->name ?? 'Antree' }}"
                        });
                        
                        if (!printSuccess) {
                            console.warn('Physical printing failed, showing digital ticket only.');
                        }
                    }

                    setTimeout(() => {
                        printingState.classList.add('hidden');
                        ticketArea.classList.remove('hidden');
                        document.getElementById('res-number').innerText = data.data.queue_number;
                        document.getElementById('res-service-name').innerText = data.data.service_name;
                        document.getElementById('res-date').innerText = data.data.date;
                        document.getElementById('res-time').innerText = data.data.time;
                        document.getElementById('res-wait').innerText = "~ " + data.data.wait_time + " MINS";

                        // Update local UI state
                        const footerQueue = document.getElementById(`service-queue-${serviceId}`);
                        if (footerQueue) footerQueue.innerText = data.data.queue_number;
                        
                        const cardWait = document.getElementById(`service-wait-${serviceId}`);
                        if (cardWait) cardWait.innerText = data.data.wait_time;
                    }, 1200);
                }
            })
            .catch((err) => {
                console.error(err);
                alert("System error. Please contact helpdesk.");
                modal.classList.add('hidden');
            });
        }

        function closeModal() {
            document.getElementById('print-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
