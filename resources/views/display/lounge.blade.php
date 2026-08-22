<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Queue Display (Modern Lounge Style) - {{ config('app.name', 'Antree') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $primaryColor = $settings['primary_color'] ?? '#F43F5E';
        $accentColor  = $settings['accent_color']  ?? '#0F172A';
    @endphp

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent:  {{ $accentColor }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            background-image: radial-gradient(#CBD5E1 0.6px, transparent 0.6px);
            background-size: 32px 32px;
            color: #0F172A;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            box-sizing: border-box;
        }

        .lounge-card {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        }

        .lounge-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .card-flare {
            position: absolute;
            top: -60px;
            right: -60px;
            width: 160px;
            height: 160px;
            background: var(--primary);
            opacity: 0.06;
            filter: blur(35px);
            border-radius: 50%;
            pointer-events: none;
        }

        .youtube-cover {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
            transform: scale(1.35);
            transform-origin: center center;
        }

        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee-text {
            display: inline-block;
            padding-left: 100%;
            animation: scroll-left 30s linear infinite;
        }

        @keyframes scroll-left {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }

        .pulse-active {
            animation: pulse-ring 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="select-none flex flex-col justify-between h-screen w-screen p-0 m-0">

    <!-- Header -->
    <header class="h-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 grid grid-cols-12 items-center px-8 relative z-20 shadow-sm shrink-0">
        <!-- Logo & Brand -->
        <div class="col-span-4 flex items-center gap-3">
            @if(!empty($settings['logo_url']))
                <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center p-1 border border-slate-100 flex-shrink-0 shadow-sm">
                    <img src="{{ asset($settings['logo_url']) }}" class="w-full h-full object-contain rounded-lg" alt="Logo">
                </div>
            @elseif(isset($institution) && $institution->logo_path)
                <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center p-1 border border-slate-100 flex-shrink-0 shadow-sm">
                    <img src="{{ asset($institution->logo_path) }}" class="w-full h-full object-contain rounded-lg" alt="Logo">
                </div>
            @else
                <div class="w-11 h-11 bg-slate-900 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                    <i class="ti ti-bolt text-xl"></i>
                </div>
            @endif
            <div>
                <h1 class="text-base font-extrabold text-slate-800 tracking-tight leading-none uppercase">{{ $settings['company_name'] ?? 'Lounge Antree' }}</h1>
                <p class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-1">{{ $institution->address ?? 'Sistem Antrean Digital' }}</p>
            </div>
        </div>

        <!-- Clock Widget -->
        <div class="col-span-4 flex justify-center">
            <div class="text-center bg-slate-50 border border-slate-200/60 rounded-2xl px-5 py-1.5 flex items-center gap-4">
                <p id="hdr-time" class="text-lg font-black text-slate-850 tabular-nums leading-none">00:00:00</p>
                <span class="text-slate-355 text-xs">/</span>
                <p id="hdr-date" class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none">Jum, 21 Agt 2026</p>
            </div>
        </div>

        <!-- Audio Unlock -->
        <div class="col-span-4 flex items-center justify-end gap-3">
            <div id="audio-unlock" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 text-[10px] font-extrabold uppercase tracking-widest rounded-xl flex items-center gap-2 cursor-pointer transition relative">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping absolute -top-0.5 -right-0.5" id="audio-ping"></span>
                <i class="ti ti-volume text-sm"></i>
                <span id="audio-status-text">Aktifkan Suara</span>
            </div>
            <div id="fullscreen-toggle" onclick="toggleFullscreen()" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200/60 text-[10px] font-extrabold uppercase tracking-widest rounded-xl flex items-center gap-2 cursor-pointer transition">
                <i class="ti ti-maximize text-sm" id="fs-icon"></i>
                <span id="fs-text">Layar Penuh</span>
            </div>
        </div>
    </header>

    <!-- Main Workspace -->
    <div class="relative z-10 flex-1 grid grid-cols-12 gap-6 p-6 overflow-hidden min-h-0" style="height: calc(100vh - 130px);">
        
        <!-- Left Column: Video & Running Text (7 cols) -->
        <div class="col-span-7 flex flex-col gap-6 h-full overflow-hidden">
            <!-- Media Player Container -->
            <div class="flex-1 bg-black rounded-3xl relative overflow-hidden shadow-sm border border-slate-200/60">
                @forelse($media as $m)
                    @if($m->type === 'video')
                        <video class="w-full h-full object-cover absolute inset-0" autoplay muted loop><source src="{{ asset($m->content) }}" type="video/mp4"></video>
                    @elseif($m->type === 'youtube')
                        <iframe class="youtube-cover" src="https://www.youtube.com/embed/{{ $m->content }}?autoplay=1&mute=1&loop=1&playlist={{ $m->content }}&controls=0&showinfo=0&rel=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    @endif
                @empty
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-8 bg-slate-900 text-center">
                        <i class="ti ti-video text-5xl text-rose-500 mb-4 animate-pulse"></i>
                        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Antree Media Player</h2>
                        <p class="text-xs text-slate-400 mt-2">Tambahkan URL YouTube atau berkas MP4 melalui Display Settings admin.</p>
                    </div>
                @endforelse
            </div>

            <!-- Running Text -->
            <div class="h-14 bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl flex items-center px-6 shadow-sm overflow-hidden shrink-0">
                <div class="marquee-container flex-1">
                    <div class="marquee-text text-sm font-bold text-slate-655 uppercase tracking-wider">
                        {{ $settings['running_text'] ?? 'Selamat datang di Antree. Silakan mengambil nomor antrean Anda dan tunggu hingga nomor Anda dipanggil.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Active Counters Grid (5 cols) -->
        <div class="col-span-5 flex flex-col gap-3.5 overflow-y-auto custom-scrollbar h-full pr-1">
            @forelse($counters as $counter)
                @php
                    $serving = $counter->queues->first();
                    $color = $counter->serviceType->color ?? '#F43F5E';
                @endphp
                <div class="rounded-2xl flex items-center justify-between px-6 py-5 relative overflow-hidden border border-white/30 shadow-sm" style="background: linear-gradient(135deg, {{ $color }}dd, {{ $color }}f2);" id="counter-card-{{ $counter->id }}">
                    <div class="card-flare" style="background-color: #ffffff; opacity: 0.15; width: 140px; height: 140px; top: -40px; right: -40px;"></div>
                    
                    <div class="flex items-center gap-4 relative z-10 min-w-0 flex-1">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-white/20 backdrop-blur-sm shrink-0">
                            <i class="ti ti-device-desktop text-xl text-white"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-extrabold text-white leading-tight truncate">{{ $counter->name }}</h3>
                            <p class="text-[9px] font-bold text-white/90 uppercase tracking-widest mt-0.5 truncate">{{ $counter->serviceType->name }}</p>
                        </div>
                    </div>

                    <div class="shrink-0 text-right pl-5 border-l border-white/20 relative z-10 ml-4">
                        <p class="text-[8px] font-black text-white/80 uppercase tracking-widest mb-0.5">ANTREAN</p>
                        <p id="queue-num-{{ $counter->id }}" class="text-2xl font-black text-white font-mono leading-none mt-1 tabular-nums">
                            {{ $serving?->queue_number ?? '—' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex-1 bg-white/80 border border-slate-200/60 rounded-3xl flex flex-col items-center justify-center p-8 text-center">
                    <i class="ti ti-device-desktop-off text-4xl text-slate-300 mb-3 animate-pulse"></i>
                    <h3 class="text-sm font-bold text-slate-700">Belum Ada Loket Aktif</h3>
                </div>
            @endforelse
        </div>

    </div>

    <!-- Audio & Echo Calling Popup Overlay -->
    <div id="call-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md flex items-center justify-center z-50 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-white border border-slate-200 p-10 rounded-[2.5rem] max-w-sm w-full mx-6 text-center shadow-2xl scale-90 transition-transform duration-300 popup-card-inner">
            <span class="text-[10px] font-bold uppercase tracking-widest text-rose-500">Panggilan Antrean</span>
            <div id="popup-number" class="text-7xl font-black text-slate-800 leading-none tracking-tighter my-6 font-mono">A001</div>
            
            <div class="inline-block px-8 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl w-full">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Silakan Menuju Ke</span>
                <div id="popup-counter" class="text-2xl font-extrabold tracking-tight text-slate-800 mt-1 uppercase">Loket 1</div>
            </div>
        </div>
    </div>

    <!-- Clock & Realtime Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const timeEl = document.getElementById('hdr-time');
            const dateEl = document.getElementById('hdr-date');

            if (timeEl) timeEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        let idVoice = null;
        function loadVoices() {
            const voices = speechSynthesis.getVoices();
            idVoice = voices.find(v => v.lang.toLowerCase().includes('id') || v.name.toLowerCase().includes('indonesia'));
        }
        speechSynthesis.onvoiceschanged = loadVoices;
        loadVoices();

        function unlockAudio() {
            const statusText = document.getElementById('audio-status-text');
            const unlockBtn = document.getElementById('audio-unlock');
            const pingDot = document.getElementById('audio-ping');
            
            const u = new SpeechSynthesisUtterance('Suara diaktifkan');
            u.lang = 'id-ID';
            if (idVoice) u.voice = idVoice;
            
            u.onend = () => {
                statusText.textContent = 'Suara Aktif';
                if (pingDot) pingDot.remove();
                unlockBtn.style.opacity = '0.7';
                unlockBtn.classList.add('pointer-events-none');
            };
            speechSynthesis.speak(u);
        }
        document.getElementById('audio-unlock').addEventListener('click', unlockAudio);

        function showCall(number, counterName) {
            document.getElementById('popup-number').textContent = number;
            document.getElementById('popup-counter').textContent = counterName;
            
            const overlay = document.getElementById('call-overlay');
            const popupInner = overlay.querySelector('.popup-card-inner');
            
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            popupInner.classList.remove('scale-90');
            popupInner.classList.add('scale-100');

            const cleanNumber = number.split('').join(' ').replace(/0/g, 'kosong');
            const utteranceText = `Nomor antrian, ${cleanNumber}. Silahkan menuju ke, ${counterName}`;
            
            speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(utteranceText);
            utterance.lang = 'id-ID';
            utterance.rate = 0.85;
            if (idVoice) utterance.voice = idVoice;

            setTimeout(() => {
                speechSynthesis.speak(utterance);
            }, 200);

            setTimeout(() => {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                popupInner.classList.remove('scale-100');
                popupInner.classList.add('scale-90');
            }, 5500);
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable fullscreen: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        }
        document.addEventListener('fullscreenchange', () => {
            const icon = document.getElementById('fs-icon');
            const text = document.getElementById('fs-text');
            if (icon && text) {
                if (document.fullscreenElement) {
                    icon.className = 'ti ti-minimize text-sm';
                    text.textContent = 'Keluar';
                } else {
                    icon.className = 'ti ti-maximize text-sm';
                    text.textContent = 'Layar Penuh';
                }
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.channel('queue-channel')
                    .listen('.queue.called', (e) => {
                        showCall(e.queueNumber, e.counterName);
                        const numEl = document.getElementById(`queue-num-${e.counterId}`);
                        if (numEl) numEl.textContent = e.queueNumber;
                    });
            }
        });
    </script>
</body>
</html>
