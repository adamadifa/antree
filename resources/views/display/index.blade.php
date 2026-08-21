<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Queue Display - {{ config('app.name', 'Antree') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $primaryColor = $settings['primary_color'] ?? '#0d9488';
        $accentColor  = $settings['accent_color']  ?? '#0f172a';
    @endphp

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent:  {{ $accentColor }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
            background: #f8fafc;
        }

        /* Continuous Full-Bleed Layout with 0 margins, 0 padding, and 0 gaps */
        .display-layout {
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            padding: 0;
            margin: 0;
            gap: 0;
            padding-top: 80px;    /* Space for fixed top header */
            padding-bottom: 50px; /* Space for fixed bottom footer */
            box-sizing: border-box;
            background: #f1f5f9;
        }

        .text-glow-white {
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.35);
        }

        .text-glow-primary {
            text-shadow: 0 0 25px rgba({{ hexdec(substr(str_replace('#','',$primaryColor),0,2)) }}, {{ hexdec(substr(str_replace('#','',$primaryColor),2,2)) }}, {{ hexdec(substr(str_replace('#','',$primaryColor),4,2)) }}, 0.15);
        }

        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee-text {
            display: inline-block;
            padding-left: 100%;
            animation: scroll-left 45s linear infinite;
        }

        @keyframes scroll-left {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* YouTube Crop Cover Zoom */
        .youtube-cover {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
            transform: scale(1.35); /* Zoom in to crop black bars */
            transform-origin: center center;
        }

        /* Call Overlay Glow Animations */
        @keyframes overlayPulse {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(0,0,0,0)); }
            50% { transform: scale(1.02); filter: drop-shadow(0 4px 25px rgba(13, 148, 136, 0.1)); }
        }

        .call-popup-number-glow {
            animation: overlayPulse 2.5s infinite ease-in-out;
        }
    </style>
</head>
<body class="text-slate-800 select-none">

    <div class="display-layout">

        <!-- ===== HEADER (DEEP SLATE-900 WITH BOLD PRIMARY COLOR BOTTOM ACCENT BORDER) ===== -->
        <header class="fixed top-0 left-0 right-0 w-full px-6 py-4 flex items-center justify-between shadow-md transition duration-200 z-40"
                style="background: #0f172a; border-bottom: 3px solid var(--primary); height: 80px;">
            <div class="flex items-center gap-4">
                @if(!empty($settings['logo_url']))
                    <div class="w-12 h-12 bg-white rounded-xl p-1.5 flex items-center justify-center border border-slate-700/50 shadow-sm">
                        @if(str_starts_with($settings['logo_url'], 'http://') || str_starts_with($settings['logo_url'], 'https://'))
                            <img src="{{ $settings['logo_url'] }}" class="h-full w-full object-contain" alt="Logo">
                        @elseif(str_starts_with($settings['logo_url'], 'storage/'))
                            <img src="{{ asset($settings['logo_url']) }}" class="h-full w-full object-contain" alt="Logo">
                        @else
                            <img src="{{ asset('storage/' . $settings['logo_url']) }}" class="h-full w-full object-contain" alt="Logo">
                        @endif
                    </div>
                @elseif(isset($institution) && $institution->logo_path)
                    <div class="w-12 h-12 bg-white rounded-xl p-1.5 flex items-center justify-center border border-slate-700/50 shadow-sm">
                        <img src="{{ asset($institution->logo_path) }}" class="h-full w-full object-contain" alt="Logo">
                    </div>
                @else
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color: var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                @endif
                <div>
                    <h1 class="text-lg font-extrabold tracking-tight text-white leading-none mb-1.5">{{ $institution->app_name ?? $settings['company_name'] ?? $settings['name'] ?? 'Antree' }}</h1>
                    <p class="text-[11px] font-bold text-slate-300 tracking-normal uppercase">{{ $institution->name ?? $settings['slogan'] ?? 'Sistem Antrean Real-time' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Audio Unlocker -->
                <div id="audio-unlock" class="relative group px-4 py-2 bg-white/5 hover:bg-white/10 border border-slate-700 rounded-full text-[10px] font-bold uppercase tracking-wider text-white flex items-center gap-2 cursor-pointer transition duration-150">
                    <span class="w-2 h-2 rounded-full bg-white animate-ping absolute -top-0.5 -right-0.5" id="audio-ping"></span>
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                    <span id="audio-status-text">Aktifkan Suara</span>
                    
                    <div class="absolute right-0 top-11 bg-white border border-slate-200 p-3 rounded-xl w-60 text-[9px] leading-relaxed text-slate-500 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-150 pointer-events-none z-50">
                        <b class="text-teal-600">Pemberitahuan:</b> Klik di sini agar sistem suara pemanggil aktif otomatis setelah browser dimuat ulang.
                    </div>
                </div>

                <!-- Clock Widgets -->
                <div class="text-right border-l border-slate-700 pl-6" style="height: 38px;">
                    <div id="hdr-time" class="text-2xl font-black text-white tracking-tight leading-none mb-1">--:--:--</div>
                    <div id="hdr-date" class="text-[10px] font-bold text-slate-300 uppercase tracking-wider leading-none">—</div>
                </div>
            </div>
        </header>

        <!-- ===== MAIN SECTION (SEAMLESS SPLIT-SCREEN PANEL, FIXED FULL-WIDTH, NO GAP - HEIGHT 500PX) ===== -->
        <div class="grid grid-cols-12 gap-0 h-[500px] min-h-[500px]">
            <!-- Left Panel: Now Serving centerpiece (Solid White, Sharp corners, borderless right) -->
            <div class="col-span-5 flex flex-col overflow-hidden transition duration-200"
                 style="background: #ffffff; border-radius: 0; border: none; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <div class="px-6 py-4 border-b flex items-center justify-between"
                     style="background: #f8fafc; border-color: #e2e8f0;">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-500">Nomor Antrean</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold tracking-wider text-emerald-600 bg-emerald-50 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                        Sedang Dilayani
                    </span>
                </div>
                
                <div class="flex-1 flex flex-col items-center justify-center p-6">
                    <h1 id="main-number" class="text-[9.5rem] font-black tracking-tighter leading-none select-none animate-pulse text-glow-primary"
                        style="color: var(--primary);">
                        {{ $lastCalled?->queue_number ?? '—' }}
                    </h1>
                </div>

                <div id="main-counter" class="px-6 py-4 text-white text-center text-lg font-extrabold tracking-tight" style="background: var(--accent)">
                    {{ $lastCalled ? $lastCalled->counter->name . ' — ' . $lastCalled->serviceType->name : 'Menunggu Panggilan Antrean' }}
                </div>
            </div>

            <!-- Right Panel: Full-screen Media Player (Sharp corners, borderless left) -->
            <div class="col-span-7 overflow-hidden relative" style="border-radius: 0; border: none; border-bottom: 1px solid #e2e8f0; background: #000000;">
                @forelse($media as $m)
                    @if($m->type === 'video')
                        <video class="w-full h-full object-cover absolute inset-0" autoplay muted loop><source src="{{ asset($m->content) }}" type="video/mp4"></video>
                    @elseif($m->type === 'youtube')
                        <iframe class="youtube-cover" src="https://www.youtube.com/embed/{{ $m->content }}?autoplay=1&mute=1&loop=1&playlist={{ $m->content }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    @endif
                @empty
                    <!-- Premium Light Info Visualizer Card -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-8 bg-white text-center">
                        <div class="w-16 h-16 bg-teal-50 border border-teal-100 rounded-2xl flex items-center justify-center text-teal-600 mb-5 animate-pulse shadow-sm shadow-teal-100/5">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight leading-none mb-2">{{ $institution->app_name ?? $settings['company_name'] ?? 'Antree' }}</h2>
                        <p class="text-xs text-slate-500 leading-relaxed max-w-sm">{{ $institution->address ?? 'Selamat datang di area pelayanan. Silakan mengambil nomor antrean dan menunggu loket memanggil nomor Anda.' }}</p>
                        
                        <div class="mt-6 flex gap-6 border-t border-slate-200/80 pt-4 w-full max-w-xs justify-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Server Active</span>
                            <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span> Real-time Sync</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ===== COUNTERS HORIZONTAL GRID STRIP (SOLID SERVICE COLORS, ELEVATED & HIGH CONTRAST) ===== -->
        <div class="flex-1 grid gap-0 w-full" 
             style="grid-template-columns: repeat({{ max(1, $counters->count()) }}, minmax(0, 1fr));">
            @foreach($counters as $counter)
                @php
                    $serving = $counter->queues->first();
                    $svcColor = $counter->serviceType->color ?? $primaryColor;
                @endphp
                <div class="p-6 flex flex-col justify-between hover:scale-[1.005] transition duration-200 border-r relative overflow-hidden"
                     style="background: {{ $svcColor }}; border-color: rgba(255, 255, 255, 0.15); border-radius: 0; box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);">
                    
                    {{-- Clean overlay to enrich color depth --}}
                    <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-black/10 pointer-events-none"></div>
                    
                    <div class="relative z-10 flex items-center justify-between gap-2 border-b pb-3 mb-2"
                         style="border-color: rgba(255, 255, 255, 0.2);">
                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded bg-slate-950/25 text-white border border-white/10">{{ $counter->serviceType->name }}</span>
                        <span class="text-xs font-black uppercase tracking-wider text-white drop-shadow-sm">{{ $counter->name }}</span>
                    </div>
                    
                    <div class="relative z-10 flex-1 flex items-center justify-center py-4">
                        <span id="counter-number-{{ $counter->id }}" class="text-7xl font-black tracking-tight select-none text-white drop-shadow-[0_4px_12px_rgba(0,0,0,0.25)] text-glow-white">
                            {{ $serving?->queue_number ?? '—' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ===== FULL-WIDTH RUNNING TEXT (SOLID ACCENT COLOR, UPWARD SHADOW) ===== -->
        <footer class="fixed bottom-0 left-0 right-0 w-full border-t py-3.5 px-6 marquee-container h-[50px] min-h-[50px] flex items-center z-40"
                style="background: var(--accent); border-color: rgba(255, 255, 255, 0.08); border-radius: 0; box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.2);">
            <div class="marquee-text text-sm font-semibold text-white tracking-wide">
                {{ $settings['running_text'] ?? 'Selamat datang. Silahkan ambil nomor antrian dan tunggu sampai nomor Anda dipanggil. Terima kasih.' }}
            </div>
        </footer>

    </div>

    <!-- ===== CALLING OVERLAY ===== -->
    <div id="call-overlay" class="fixed inset-0 bg-slate-900/40 backdrop-filter backdrop-blur-xl flex items-center justify-center z-50 opacity-0 visibility-hidden transition-all duration-300 pointer-events-none">
        <div class="bg-white p-12 rounded-[2.5rem] max-w-xl w-full mx-6 text-center border border-slate-100 shadow-2xl scale-90 transition-transform duration-300 popup-card-inner">
            <div class="text-xs font-black uppercase tracking-[0.2em] mb-6" style="color: var(--primary)">Sedang Memanggil</div>
            <div id="popup-number" class="text-8xl font-black text-slate-900 leading-none tracking-tighter mb-8 select-none text-glow-slate">A-001</div>
            
            <div class="inline-block px-10 py-5 bg-slate-50 border border-slate-100 rounded-2xl w-full">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Silahkan Menuju Ke</div>
                <div id="popup-counter" class="text-3xl font-extrabold tracking-tight" style="color: var(--primary)">Loket 1</div>
            </div>
        </div>
    </div>

    <script>
        // Clock & Date Logic
        function tick() {
            const now = new Date();
            document.getElementById('hdr-time').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
            const day = now.toLocaleDateString('id-ID', { weekday: 'long' });
            const date = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('hdr-date').textContent = `${day}, ${date}`;
        }
        tick(); setInterval(tick, 1000);

        // Voice Synthesizer Setup
        let idVoice = null;
        function loadVoices() {
            const voices = speechSynthesis.getVoices();
            console.log('Available voices:', voices.length);
            idVoice = voices.find(v => v.lang.toLowerCase().includes('id') || v.name.toLowerCase().includes('indonesia'));
            if (idVoice) {
                console.log('Selected voice:', idVoice.name);
            }
        }
        speechSynthesis.onvoiceschanged = loadVoices;
        loadVoices();

        // Manual speech trigger
        function unlockAudio() {
            const statusText = document.getElementById('audio-status-text');
            const unlockBtn = document.getElementById('audio-unlock');
            const pingDot = document.getElementById('audio-ping');
            
            const u = new SpeechSynthesisUtterance('Suara diaktifkan');
            u.lang = 'id-ID';
            if (idVoice) u.voice = idVoice;
            u.volume = 1; 
            
            u.onend = () => {
                statusText.textContent = 'Suara Aktif';
                if (pingDot) pingDot.remove();
                unlockBtn.style.opacity = '0.7';
            };
            
            speechSynthesis.speak(u);
        }

        function checkAutoplay() {
            const u = new SpeechSynthesisUtterance('');
            u.volume = 0;
            u.onend = () => {
                const unlockBtn = document.getElementById('audio-unlock');
                if (unlockBtn) unlockBtn.style.display = 'none';
            };
            speechSynthesis.speak(u);
        }

        let currentUtterance = null; // Prevent GC

        // Display Calling Popups
        function showCall(number, counterName) {
            console.log('Calling:', number, 'at', counterName);
            document.getElementById('popup-number').textContent = number;
            document.getElementById('popup-counter').textContent = counterName;
            
            const overlay = document.getElementById('call-overlay');
            const popupInner = overlay.querySelector('.popup-card-inner');
            
            // Show Overlay
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
            popupInner.classList.remove('scale-90');
            popupInner.classList.add('scale-100');

            // Pronounce number (e.g. A-001 -> "A, kosong kosong satu")
            const cleanNumber = number.split('-').map(part => part.split('').join(' ')).join(', ').replace(/0/g, 'kosong');
            const utteranceText = `Nomor antrian, ${cleanNumber}. Silahkan menuju ke, ${counterName}`;
            
            speechSynthesis.cancel();
            
            currentUtterance = new SpeechSynthesisUtterance(utteranceText);
            currentUtterance.lang = 'id-ID';
            currentUtterance.rate = 0.85;
            currentUtterance.pitch = 1.0;
            
            if (idVoice) {
                currentUtterance.voice = idVoice;
            }

            setTimeout(() => {
                speechSynthesis.resume();
                speechSynthesis.speak(currentUtterance);
            }, 150);

            // Auto-hide popup after voice announcement
            setTimeout(() => {
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                popupInner.classList.remove('scale-100');
                popupInner.classList.add('scale-90');
            }, 5500);
        }

        // Live Reverb Channels Echo Listeners
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(checkAutoplay, 1200);

            if (window.Echo) {
                window.Echo.channel('queue-channel')
                    .listen('.queue.called', (e) => {
                        console.log('Real-time event received:', e);
                        
                        const mainNum = document.getElementById('main-number');
                        const mainCnt = document.getElementById('main-counter');
                        
                        if (mainNum) mainNum.textContent = e.queueNumber;
                        if (mainCnt) mainCnt.textContent = `${e.counterName} — ${e.serviceName}`;
                        
                        showCall(e.queueNumber, e.counterName);

                        const counterNum = document.getElementById(`counter-number-${e.counterId}`);
                        if (counterNum) counterNum.textContent = e.queueNumber;
                    });
            } else {
                console.error('Laravel Echo is missing. Real-time updates disabled.');
            }
        });
    </script>
</body>
</html>
