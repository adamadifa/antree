<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Queue Display (List Counter) - {{ config('app.name', 'Antree') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $primaryColor = $settings['primary_color'] ?? '#D9488'; // Orange/teal
        $accentColor  = $settings['accent_color']  ?? '#004e7c'; // Corporate blue
    @endphp

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent:  {{ $accentColor }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            color: #ffffff;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            box-sizing: border-box;
        }

        /* YouTube iframe zoom/crop cover */
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
            animation: scroll-left 45s linear infinite;
        }

        @keyframes scroll-left {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* Call overlay pulse */
        @keyframes overlayPulse {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 15px rgba(0,0,0,0)); }
            50% { transform: scale(1.02); filter: drop-shadow(0 4px 30px rgba(249, 115, 22, 0.15)); }
        }

        .call-popup-glow {
            animation: overlayPulse 2.5s infinite ease-in-out;
        }
    </style>
</head>
<body class="select-none flex flex-col justify-between h-screen w-screen p-0 m-0">

    <!-- Ambient Glow background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0 opacity-20">
        <div class="absolute -top-[10%] left-1/3 w-[800px] h-[500px] bg-sky-900/50 blur-[130px] rounded-full"></div>
    </div>

    <!-- MAIN DISPLAY WRAPPER (Split Screen Layout) -->
    <div class="relative z-10 flex-1 grid grid-cols-12 gap-0 overflow-hidden" style="height: calc(100vh - 54px);">
        
        <!-- ================= LEFT PANEL (Logo & Video Player) ================= -->
        <div class="col-span-7 flex flex-col justify-between bg-slate-950 border-r border-slate-800/80">
            <!-- Top-Left Branding -->
            <div class="p-5 flex items-center justify-between border-b border-slate-800/50 bg-slate-950">
                <div class="flex items-center gap-4">
                    @if(!empty($settings['logo_url']))
                        <div class="w-12 h-12 bg-white rounded-xl p-1.5 flex items-center justify-center border border-slate-800 shadow-md">
                            @if(str_starts_with($settings['logo_url'], 'http://') || str_starts_with($settings['logo_url'], 'https://'))
                                <img src="{{ $settings['logo_url'] }}" class="h-full w-full object-contain" alt="Logo">
                            @elseif(str_starts_with($settings['logo_url'], 'storage/'))
                                <img src="{{ asset($settings['logo_url']) }}" class="h-full w-full object-contain" alt="Logo">
                            @else
                                <img src="{{ asset('storage/' . $settings['logo_url']) }}" class="h-full w-full object-contain" alt="Logo">
                            @endif
                        </div>
                    @elseif(isset($institution) && $institution->logo_path)
                        <div class="w-12 h-12 bg-white rounded-xl p-1.5 flex items-center justify-center border border-slate-800 shadow-md">
                            <img src="{{ asset($institution->logo_path) }}" class="h-full w-full object-contain" alt="Logo">
                        </div>
                    @else
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight text-white leading-none mb-1">{{ $institution->app_name ?? $settings['company_name'] ?? 'Antree' }}</h1>
                        <p class="text-xs font-bold text-slate-400 tracking-wide uppercase">{{ $institution->name ?? $settings['slogan'] ?? 'Sistem Antrean' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Audio Unlocker -->
                    <div id="audio-unlock" class="relative group px-4.5 py-2.5 bg-slate-900 hover:bg-slate-800 border border-slate-700 rounded-full text-[10px] font-bold uppercase tracking-wider text-white flex items-center gap-2 cursor-pointer transition duration-150">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-ping absolute -top-0.5 -right-0.5" id="audio-ping"></span>
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                        <span id="audio-status-text">Aktifkan Suara</span>
                    </div>
                    <!-- Fullscreen Toggle -->
                    <div id="fullscreen-toggle" onclick="toggleFullscreen()" class="relative group px-4.5 py-2.5 bg-slate-900 hover:bg-slate-800 border border-slate-700 rounded-full text-[10px] font-bold uppercase tracking-wider text-white flex items-center gap-2 cursor-pointer transition duration-150">
                        <i class="ti ti-maximize text-sm" id="fs-icon"></i>
                        <span id="fs-text">Layar Penuh</span>
                    </div>
                </div>
            </div>

            <!-- Video Player Display Container -->
            <div class="flex-1 w-full overflow-hidden relative bg-black">
                @forelse($media as $m)
                    @if($m->type === 'video')
                        <video class="w-full h-full object-cover absolute inset-0" autoplay muted loop><source src="{{ asset($m->content) }}" type="video/mp4"></video>
                    @elseif($m->type === 'youtube')
                        <iframe class="youtube-cover" src="https://www.youtube.com/embed/{{ $m->content }}?autoplay=1&mute=1&loop=1&playlist={{ $m->content }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    @endif
                @empty
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-8 bg-slate-900 text-center">
                        <div class="w-16 h-16 bg-slate-800 border border-slate-700 rounded-2xl flex items-center justify-center text-orange-500 mb-5 animate-pulse">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h2 class="text-xl font-extrabold text-white tracking-tight mb-2">{{ $institution->app_name ?? $settings['company_name'] ?? 'Antree' }}</h2>
                        <p class="text-xs text-slate-400 leading-relaxed max-w-sm">{{ $institution->address ?? 'Selamat datang. Silakan mengambil nomor antrean dan menunggu loket memanggil nomor Anda.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= RIGHT PANEL (Callbox & Table status) ================= -->
        <div class="col-span-5 flex flex-col bg-slate-900 overflow-hidden">
            
            <!-- Showcase / Call Box Header (BNI style top-right) -->
            <div class="grid grid-cols-12 gap-0 border-b border-slate-800/80">
                <!-- Current called queue -->
                <div class="col-span-7 bg-orange-600 p-5 flex flex-col justify-center items-center text-center shadow-lg relative z-10">
                    <span id="call-box-label" class="text-xs font-black tracking-widest text-orange-100 uppercase mb-1">NO. ANTRIAN</span>
                    <h2 id="showcase-number" class="text-6xl font-black text-white tracking-tight leading-none">
                        {{ $lastCalled?->queue_number ?? '—' }}
                    </h2>
                </div>
                <!-- Target counter -->
                <div class="col-span-5 bg-slate-950 p-5 flex flex-col justify-center items-center text-center border-l border-slate-800/50">
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none mb-2">MENUJU LOKET</span>
                    <h2 id="showcase-counter" class="text-6xl font-black text-white leading-none tracking-tighter">
                        {{ $lastCalled ? filter_var($lastCalled->counter->name, FILTER_SANITIZE_NUMBER_INT) : '—' }}
                    </h2>
                </div>
            </div>

            <!-- List Counters Table -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-950/80 border-b border-slate-800">
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">NO. ANTRIAN</th>
                            <th class="py-4 px-6 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">LOKET / COUNTER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($counters as $index => $counter)
                            @php
                                $serving = $counter->queues->first();
                                $rowBg = $index % 2 === 0 ? 'bg-slate-900' : 'bg-slate-900/50';
                                $svcColor = $counter->serviceType->color ?? $primaryColor;
                            @endphp
                            <tr class="{{ $rowBg }} border-b border-slate-800/50 hover:bg-slate-850 transition duration-150">
                                <td class="py-5.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $svcColor }}"></span>
                                        <span id="list-queue-{{ $counter->id }}" class="text-4xl font-extrabold tracking-tight text-white select-none">
                                            {{ $serving?->queue_number ?? '—' }}
                                        </span>
                                        <span class="text-[10px] font-semibold text-slate-500 uppercase ml-2 tracking-wider">({{ $counter->serviceType->name }})</span>
                                    </div>
                                </td>
                                <td class="py-5.5 px-6 text-right">
                                    <span class="text-4xl font-black text-white tracking-tight">
                                        {{ filter_var($counter->name, FILTER_SANITIZE_NUMBER_INT) ?: $counter->name }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ===== FOOTER (Running Text & Clock) ===== -->
    <footer class="h-[54px] min-h-[54px] bg-orange-600 border-t border-orange-500 flex items-center justify-between px-6 relative z-20">
        <!-- Running Text -->
        <div class="marquee-container flex-1 mr-8">
            <div class="marquee-text text-sm font-bold text-white tracking-wide">
                {{ $settings['running_text'] ?? 'Selamat datang. Silakan ambil nomor antrean dan tunggu sampai nomor Anda dipanggil. Terima kasih.' }}
            </div>
        </div>

        <!-- Date & Live clock widget -->
        <div class="flex items-center gap-4 text-white pl-6 border-l border-white/20 select-none">
            <div id="hdr-date" class="text-xs font-semibold uppercase tracking-wider text-orange-100 hidden md:block">—</div>
            <div id="hdr-time" class="text-xl font-black tracking-tight tabular-nums">00.00.00</div>
        </div>
    </footer>

    <!-- ===== CALLING OVERLAY ===== -->
    <div id="call-overlay" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md flex items-center justify-center z-50 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-slate-900 border border-slate-800 p-12 rounded-[2.5rem] max-w-xl w-full mx-6 text-center shadow-2xl scale-90 transition-transform duration-300 popup-card-inner call-popup-glow">
            <div class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-orange-500">Sedang Memanggil</div>
            <div id="popup-number" class="text-8xl font-black text-white leading-none tracking-tighter mb-8 select-none">A-001</div>
            
            <div class="inline-block px-10 py-5 bg-slate-950 border border-slate-850 rounded-2xl w-full">
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Silahkan Menuju Ke</div>
                <div id="popup-counter" class="text-3xl font-extrabold tracking-tight text-white">Loket 1</div>
            </div>
        </div>
    </div>

    <!-- Core Scripting Logic -->
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

        // Voice Synthesizer
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

        function checkAutoplay() {
            const u = new SpeechSynthesisUtterance('');
            u.volume = 0;
            u.onend = () => {
                const unlockBtn = document.getElementById('audio-unlock');
                if (unlockBtn) unlockBtn.style.display = 'none';
            };
            speechSynthesis.speak(u);
        }

        let currentUtterance = null;

        function showCall(number, counterName) {
            console.log('Calling:', number, 'at', counterName);
            document.getElementById('popup-number').textContent = number;
            document.getElementById('popup-counter').textContent = counterName;
            
            const overlay = document.getElementById('call-overlay');
            const popupInner = overlay.querySelector('.popup-card-inner');
            
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            popupInner.classList.remove('scale-90');
            popupInner.classList.add('scale-100');

            const cleanNumber = number.split('-').map(part => part.split('').join(' ')).join(', ').replace(/0/g, 'kosong');
            const utteranceText = `Nomor antrian, ${cleanNumber}. Silahkan menuju ke, ${counterName}`;
            
            speechSynthesis.cancel();
            
            currentUtterance = new SpeechSynthesisUtterance(utteranceText);
            currentUtterance.lang = 'id-ID';
            currentUtterance.rate = 0.85;
            currentUtterance.pitch = 1.0;
            if (idVoice) currentUtterance.voice = idVoice;

            setTimeout(() => {
                speechSynthesis.resume();
                speechSynthesis.speak(currentUtterance);
            }, 150);

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

        // Live Reverb Listeners
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(checkAutoplay, 1200);

            if (window.Echo) {
                window.Echo.channel('queue-channel')
                    .listen('.queue.called', (e) => {
                        console.log('Real-time event received:', e);
                        
                        // Update Showcase Box
                        const showcaseNum = document.getElementById('showcase-number');
                        const showcaseCnt = document.getElementById('showcase-counter');
                        
                        if (showcaseNum) showcaseNum.textContent = e.queueNumber;
                        if (showcaseCnt) {
                            // Extract digits only for Loket (e.g. Loket 1 -> 1)
                            const counterDigits = e.counterName.replace(/\D/g, '');
                            showcaseCnt.textContent = counterDigits || e.counterName;
                        }
                        
                        // Trigger voice popup
                        showCall(e.queueNumber, e.counterName);

                        // Update Table Row
                        const listQueue = document.getElementById(`list-queue-${e.counterId}`);
                        if (listQueue) listQueue.textContent = e.queueNumber;
                    });
            } else {
                console.error('Laravel Echo not found. Real-time updates disabled.');
            }
        });
    </script>
</body>
</html>
