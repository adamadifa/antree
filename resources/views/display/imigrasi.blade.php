<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Queue Display (Imigrasi Sumbawa Style) - {{ config('app.name', 'Antree') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $primaryColor = $settings['primary_color'] ?? '#ffd000'; // Yellow accent color
        $accentColor  = $settings['accent_color']  ?? '#0c1e43'; // Navy Blue background
    @endphp

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent:  {{ $accentColor }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--accent);
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
            animation: scroll-left 35s linear infinite;
        }

        @keyframes scroll-left {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* Call overlay pulse */
        @keyframes overlayPulse {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 15px rgba(255, 208, 0, 0)); }
            50% { transform: scale(1.02); filter: drop-shadow(0 4px 30px rgba(255, 208, 0, 0.2)); }
        }

        .call-popup-glow {
            animation: overlayPulse 2.5s infinite ease-in-out;
        }
    </style>
</head>
<body class="select-none flex flex-col justify-between h-screen w-screen p-0 m-0 border-4 border-[#ffd000]">

    <!-- ================= TOP HEADER BAR ================= -->
    <header class="h-[95px] min-h-[95px] bg-[#0c1e43] border-b-4 border-[#ffd000] grid grid-cols-12 items-center px-6 relative z-20">
        {{-- Date Info --}}
        <div class="col-span-3 flex flex-col text-left">
            <div id="hdr-day" class="text-lg font-black tracking-widest text-white leading-none uppercase">SENIN</div>
            <div id="hdr-date" class="text-sm font-bold text-[#ffd000] tracking-wider mt-1">08-08-2026</div>
        </div>

        {{-- Institution Info --}}
        <div class="col-span-6 flex flex-col items-center text-center">
            <div class="flex items-center gap-3">
                @if(!empty($settings['logo_url']))
                    @if(str_starts_with($settings['logo_url'], 'http://') || str_starts_with($settings['logo_url'], 'https://'))
                        <img src="{{ $settings['logo_url'] }}" class="h-11 object-contain" alt="Logo">
                    @elseif(str_starts_with($settings['logo_url'], 'storage/'))
                        <img src="{{ asset($settings['logo_url']) }}" class="h-11 object-contain" alt="Logo">
                    @else
                        <img src="{{ asset('storage/' . $settings['logo_url']) }}" class="h-11 object-contain" alt="Logo">
                    @endif
                @elseif(isset($institution) && $institution->logo_path)
                    <img src="{{ asset($institution->logo_path) }}" class="h-11 object-contain" alt="Logo">
                @else
                    <i class="ti ti-building-community text-[#ffd000] text-3xl"></i>
                @endif
                <div class="flex flex-col text-left">
                    <h1 class="text-lg font-black tracking-wide text-[#ffd000] leading-none uppercase">{{ $institution->name ?? $settings['company_name'] ?? 'KANTOR IMIGRASI SUMBAWA' }}</h1>
                    <p class="text-[10px] font-bold text-white tracking-widest uppercase mt-1">{{ $institution->address ?? 'JALAN GARUDA, SUMBAWA BESAR' }}</p>
                </div>
            </div>
        </div>

        {{-- Clock & Audio Unlock --}}
        <div class="col-span-3 flex items-center justify-end gap-3.5">
            <!-- Audio Unlocker -->
            <div id="audio-unlock" class="px-3 py-1.5 bg-[#ffd000] text-[#0c1e43] text-[9px] font-extrabold uppercase tracking-wider rounded-lg flex items-center gap-1.5 cursor-pointer hover:bg-white transition duration-150 relative">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping absolute -top-0.5 -right-0.5" id="audio-ping"></span>
                <i class="ti ti-volume text-xs"></i>
                <span id="audio-status-text">Buka Suara</span>
            </div>
            <!-- Fullscreen Toggle -->
            <div id="fullscreen-toggle" onclick="toggleFullscreen()" class="px-3 py-1.5 bg-[#ffd000] text-[#0c1e43] text-[9px] font-extrabold uppercase tracking-wider rounded-lg flex items-center gap-1.5 cursor-pointer hover:bg-white transition duration-150">
                <i class="ti ti-maximize text-xs" id="fs-icon"></i>
                <span id="fs-text">Layar Penuh</span>
            </div>
            
            <div id="hdr-time" class="text-3xl font-black tracking-tight text-white tabular-nums">18:51</div>
        </div>
    </header>

    <!-- ================= MAIN SPLIT CONTENT AREA ================= -->
    <div class="relative z-10 flex-1 grid grid-cols-12 gap-0 overflow-hidden" style="height: calc(100vh - 149px);">
        
        <!-- ================= LEFT PANEL (3 Call Boxes) ================= -->
        <div class="col-span-3 bg-[#0c1e43] border-r-4 border-[#ffd000] p-4 flex flex-col justify-between h-full gap-4">
            @for($i = 0; $i < 3; $i++)
                @php
                    $counter = $counters[$i] ?? null;
                    $serving = $counter ? $counter->queues->first() : null;
                @endphp
                <div class="flex-1 flex flex-col bg-[#0c1e43] border-2 border-[#ffd000] rounded-xl overflow-hidden shadow-md">
                    {{-- Card Header (Yellow Header) --}}
                    <div class="bg-[#ffd000] px-3.5 py-2 flex items-center gap-2 relative">
                        {{-- Small Logo --}}
                        @if(!empty($settings['logo_url']))
                            @if(str_starts_with($settings['logo_url'], 'http://') || str_starts_with($settings['logo_url'], 'https://'))
                                <img src="{{ $settings['logo_url'] }}" class="h-5 w-5 object-contain" alt="">
                            @elseif(str_starts_with($settings['logo_url'], 'storage/'))
                                <img src="{{ asset($settings['logo_url']) }}" class="h-5 w-5 object-contain" alt="">
                            @else
                                <img src="{{ asset('storage/' . $settings['logo_url']) }}" class="h-5 w-5 object-contain" alt="">
                            @endif
                        @elseif(isset($institution) && $institution->logo_path)
                            <img src="{{ asset($institution->logo_path) }}" class="h-5 w-5 object-contain" alt="">
                        @else
                            <i class="ti ti-shield text-[#0c1e43] text-sm"></i>
                        @endif
                        <span class="text-xs font-black tracking-wide text-[#0c1e43] uppercase truncate flex-1 text-center">
                            {{ $counter ? $counter->serviceType->name : 'LAYANAN LOKET' }}
                        </span>
                    </div>
                    {{-- Card Body --}}
                    <div class="flex-1 grid grid-cols-12 items-center p-3 gap-2">
                        {{-- Queue Number --}}
                        <div class="col-span-8 flex items-center justify-center text-center">
                            <span id="left-queue-{{ $counter?->id ?? 'dummy-'.$i }}" class="text-4xl font-extrabold text-white tracking-tight select-none font-mono">
                                {{ $serving?->queue_number ?? '—' }}
                            </span>
                        </div>
                        {{-- Loket Box --}}
                        <div class="col-span-4 bg-white rounded-lg p-1.5 flex flex-col items-center justify-center text-center shadow border border-slate-100">
                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">LOKET</span>
                            <span class="text-2xl font-black text-[#0c1e43] leading-none">
                                {{ $counter ? filter_var($counter->name, FILTER_SANITIZE_NUMBER_INT) : ($i + 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- ================= CENTER PANEL (Video Player Container) ================= -->
        <div class="col-span-6 bg-black relative overflow-hidden flex flex-col justify-center items-center">
            @forelse($media as $m)
                @if($m->type === 'video')
                    <video class="w-full h-full object-cover absolute inset-0" autoplay muted loop><source src="{{ asset($m->content) }}" type="video/mp4"></video>
                @elseif($m->type === 'youtube')
                    <iframe class="youtube-cover" src="https://www.youtube.com/embed/{{ $m->content }}?autoplay=1&mute=1&loop=1&playlist={{ $m->content }}&controls=0&showinfo=0&rel=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                @endif
            @empty
                <div class="absolute inset-0 flex flex-col items-center justify-center p-8 bg-[#0c1e43]/90 text-center">
                    <i class="ti ti-video text-6xl text-[#ffd000] mb-4 animate-pulse"></i>
                    <h2 class="text-2xl font-black text-[#ffd000] uppercase tracking-wide">{{ $institution->app_name ?? $settings['company_name'] ?? 'ANTREE' }}</h2>
                    <p class="text-xs text-white/80 max-w-sm mt-2 leading-relaxed">Silakan mengambil nomor antrean dan menunggu loket memanggil nomor Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- ================= RIGHT PANEL (3 Call Boxes - Mirrored Layout) ================= -->
        <div class="col-span-3 bg-[#0c1e43] border-l-4 border-[#ffd000] p-4 flex flex-col justify-between h-full gap-4">
            @for($i = 3; $i < 6; $i++)
                @php
                    $counter = $counters[$i] ?? null;
                    $serving = $counter ? $counter->queues->first() : null;
                @endphp
                <div class="flex-1 flex flex-col bg-[#0c1e43] border-2 border-[#ffd000] rounded-xl overflow-hidden shadow-md">
                    {{-- Card Header (Yellow Header) --}}
                    <div class="bg-[#ffd000] px-3.5 py-2 flex items-center gap-2 relative">
                        <span class="text-xs font-black tracking-wide text-[#0c1e43] uppercase truncate flex-1 text-center">
                            {{ $counter ? $counter->serviceType->name : 'LAYANAN LOKET' }}
                        </span>
                        {{-- Small Logo on Right --}}
                        @if(!empty($settings['logo_url']))
                            @if(str_starts_with($settings['logo_url'], 'http://') || str_starts_with($settings['logo_url'], 'https://'))
                                <img src="{{ $settings['logo_url'] }}" class="h-5 w-5 object-contain" alt="">
                            @elseif(str_starts_with($settings['logo_url'], 'storage/'))
                                <img src="{{ asset($settings['logo_url']) }}" class="h-5 w-5 object-contain" alt="">
                            @else
                                <img src="{{ asset('storage/' . $settings['logo_url']) }}" class="h-5 w-5 object-contain" alt="">
                            @endif
                        @elseif(isset($institution) && $institution->logo_path)
                            <img src="{{ asset($institution->logo_path) }}" class="h-5 w-5 object-contain" alt="">
                        @else
                            <i class="ti ti-shield text-[#0c1e43] text-sm"></i>
                        @endif
                    </div>
                    {{-- Card Body (Mirrored: Loket on left, Queue on right) --}}
                    <div class="flex-1 grid grid-cols-12 items-center p-3 gap-2">
                        {{-- Loket Box on Left --}}
                        <div class="col-span-4 bg-white rounded-lg p-1.5 flex flex-col items-center justify-center text-center shadow border border-slate-100">
                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">LOKET</span>
                            <span class="text-2xl font-black text-[#0c1e43] leading-none">
                                {{ $counter ? filter_var($counter->name, FILTER_SANITIZE_NUMBER_INT) : ($i + 1) }}
                            </span>
                        </div>
                        {{-- Queue Number on Right --}}
                        <div class="col-span-8 flex items-center justify-center text-center">
                            <span id="right-queue-{{ $counter?->id ?? 'dummy-'.$i }}" class="text-4xl font-extrabold text-white tracking-tight select-none font-mono">
                                {{ $serving?->queue_number ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

    </div>

    <!-- ================= BOTTOM RUNNING TEXT MARQUEE ================= -->
    <footer class="h-[50px] min-h-[50px] bg-[#0c1e43] border-t-4 border-[#ffd000] flex items-center px-6 relative z-20">
        <div class="marquee-container flex-1">
            <div class="marquee-text text-sm font-black text-white uppercase tracking-wider">
                {{ $settings['running_text'] ?? 'SELAMAT DATANG DI KANTOR IMIGRASI SUMBAWA JALAN GARUDA, SUMBAWA BESAR. SILAHKAN AMBIL NOMOR ANTRIAN ANDA.' }}
            </div>
        </div>
    </footer>

    <!-- ================= CALLING OVERLAY ================= -->
    <div id="call-overlay" class="fixed inset-0 bg-[#0c1e43]/90 backdrop-blur-md flex items-center justify-center z-50 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-[#0c1e43] border-4 border-[#ffd000] p-12 rounded-[2.5rem] max-w-xl w-full mx-6 text-center shadow-2xl scale-90 transition-transform duration-300 popup-card-inner call-popup-glow">
            <div class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-[#ffd000]">Sedang Memanggil</div>
            <div id="popup-number" class="text-8xl font-black text-white leading-none tracking-tighter mb-8 select-none font-mono">A001</div>
            
            <div class="inline-block px-10 py-5 bg-[#0a1835] border border-[#ffd000]/30 rounded-2xl w-full">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Silahkan Menuju Ke</div>
                <div id="popup-counter" class="text-3xl font-extrabold tracking-tight text-[#ffd000] uppercase">Loket 1</div>
            </div>
        </div>
    </div>

    <!-- Clock & Realtime Javascript Logic -->
    <script>
        // Clock & Date Tick Handler
        function tick() {
            const now = new Date();
            
            // Format Clock: "18:51"
            document.getElementById('hdr-time').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
            
            // Format Day Name: "SENIN"
            const days = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
            document.getElementById('hdr-day').textContent = days[now.getDay()];

            // Format Date Number: "08-08-2026"
            const dd = String(now.getDate()).padStart(2, '0');
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const yyyy = now.getFullYear();
            document.getElementById('hdr-date').textContent = `${dd}-${mm}-${yyyy}`;
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

            const cleanNumber = number.split('').join(' ').replace(/0/g, 'kosong');
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
                    icon.className = 'ti ti-minimize text-xs';
                    text.textContent = 'Keluar';
                } else {
                    icon.className = 'ti ti-maximize text-xs';
                    text.textContent = 'Layar Penuh';
                }
            }
        });

        // Live websocket event listening
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(checkAutoplay, 1200);

            if (window.Echo) {
                window.Echo.channel('queue-channel')
                    .listen('.queue.called', (e) => {
                        console.log('Real-time event received:', e);
                        
                        // Update voice & show overlay popup
                        showCall(e.queueNumber, e.counterName);

                        // Dynamically update counter cell values
                        const leftQueue = document.getElementById(`left-queue-${e.counterId}`);
                        const rightQueue = document.getElementById(`right-queue-${e.counterId}`);
                        if (leftQueue) leftQueue.textContent = e.queueNumber;
                        if (rightQueue) rightQueue.textContent = e.queueNumber;
                    });
            } else {
                console.error('Laravel Echo not found. Real-time updates disabled.');
            }
        });
    </script>
</body>
</html>
