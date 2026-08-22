@extends('layouts.operator')

@section('title', 'Operator Console')

@section('content')
<style>
    .action-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -4px rgba(0, 0, 0, 0.1), 0 4px 8px -2px rgba(0, 0, 0, 0.04);
    }
    .action-card:active {
        transform: translateY(0);
    }
    .action-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        pointer-events: none;
    }
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
</style>

<div class="flex-1 grid grid-cols-12 gap-6 overflow-hidden min-h-0">
    
    <!-- Left Workspace (Col 8) -->
    <div class="col-span-12 lg:col-span-8 flex flex-col gap-5 overflow-hidden">

        <!-- Current Ticket Display -->
        <div class="flex-1 bg-white border border-slate-200/80 rounded-3xl p-8 flex flex-col justify-between relative overflow-hidden shadow-sm">
            
            <!-- Header Row -->
            <div class="flex items-center justify-between shrink-0">
                <span class="text-xs font-bold text-slate-700">Antrean Aktif</span>
                @if($activeQueue)
                    <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-550/10 text-emerald-600 rounded-full border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                    </div>
                @else
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-md">Standby</span>
                @endif
            </div>

            <!-- Big Number -->
            <div class="flex-1 flex flex-col items-center justify-center py-4">
                @if($activeQueue)
                    <h1 class="text-[10rem] leading-none font-black text-slate-800 tracking-tighter font-mono tabular-nums select-text">
                        {{ $activeQueue->queue_number }}
                    </h1>
                    <div class="inline-block px-4 py-1.5 mt-6 rounded-lg text-xs font-bold border" style="background-color: {{ $activeQueue->serviceType->color ?? '#f1f5f9' }}20; color: {{ $activeQueue->serviceType->color ?? '#64748b' }}; border-color: {{ $activeQueue->serviceType->color ?? '#e2e8f0' }}40;">
                        {{ $activeQueue->serviceType->name }}
                    </div>
                @else
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 mx-auto mb-3">
                            <i class="ti ti-user-pause text-2xl"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-500">Menunggu Panggilan Antrean</h2>
                    </div>
                @endif
            </div>

            <!-- Action Buttons Row -->
            @if($activeQueue)
            <div class="flex items-center gap-3 shrink-0 pt-5 border-t border-slate-100">
                <form action="{{ route('operator.complete', $activeQueue) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="action-card w-full py-3.5 rounded-xl text-white font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #10B981dd, #059669f2);">
                        <div class="card-flare"></div>
                        <i class="ti ti-circle-check text-base relative z-10"></i>
                        <span class="relative z-10">Selesai</span>
                    </button>
                </form>
                
                <form action="{{ route('operator.recall', $activeQueue) }}" method="POST">
                    @csrf
                    <button type="submit" class="action-card py-3.5 px-5 rounded-xl text-white font-bold text-[10px] uppercase tracking-widest flex items-center gap-2" style="background: linear-gradient(135deg, #6366F1dd, #4F46E5f2);">
                        <div class="card-flare"></div>
                        <i class="ti ti-volume text-sm relative z-10"></i>
                        <span class="relative z-10">Panggil Ulang ({{ $activeQueue->recall_count }})</span>
                    </button>
                </form>

                <button type="button" @if($serviceTypes->count() == 0) disabled @else onclick="document.getElementById('transferModal').classList.remove('hidden')" @endif
                        class="action-card py-3.5 px-5 rounded-xl text-white font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 {{ $serviceTypes->count() == 0 ? 'opacity-40 cursor-not-allowed' : '' }}" style="background: linear-gradient(135deg, #F59E0Bdd, #D97706f2);">
                    <div class="card-flare"></div>
                    <i class="ti ti-arrows-exchange relative z-10 text-sm"></i>
                    <span class="relative z-10">Transfer</span>
                </button>

                <form action="{{ route('operator.skip', $activeQueue) }}" method="POST">
                    @csrf
                    <button type="submit" class="action-card py-3.5 px-5 rounded-xl text-white font-bold text-[10px] uppercase tracking-widest flex items-center gap-2" style="background: linear-gradient(135deg, #EF4444dd, #DC2626f2);">
                        <div class="card-flare"></div>
                        <i class="ti ti-player-skip-forward text-sm relative z-10"></i>
                        <span class="relative z-10">Lewati</span>
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Call Next Button -->
        <form action="{{ route('operator.call-next') }}" method="POST" class="shrink-0">
            @csrf
            <button type="submit" class="action-card w-full py-5 rounded-2xl text-white flex items-center justify-between px-7 group" style="background: linear-gradient(135deg, #1E293Bdd, #0F172Af2);">
                <div class="card-flare"></div>
                <div class="text-left relative z-10">
                    <p class="text-xs text-slate-400 font-medium mb-1">Siap melayani?</p>
                    <span class="text-base font-extrabold text-white tracking-tight">Panggil Antrean Selanjutnya</span>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative z-10 group-hover:scale-115 transition duration-200" style="background: linear-gradient(135deg, #F43F5Edd, #E11D48f2);">
                    <i class="ti ti-chevron-right-pipe text-base text-white"></i>
                </div>
            </button>
        </form>
    </div>

    <!-- Right: Sidebar Stats (4 cols) -->
    <div class="hidden lg:flex col-span-4 flex-col gap-5 overflow-hidden h-full">
        
        <!-- Active Station -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shrink-0 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 block">Loket Aktif</span>
                <span class="text-lg font-extrabold text-slate-800 tracking-tight mt-0.5 block">{{ $counter->name }}</span>
            </div>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, {{ $counter->serviceType->color ?? '#6366F1' }}dd, {{ $counter->serviceType->color ?? '#4F46E5' }}f2);">
                <i class="ti ti-device-desktop text-base text-white"></i>
            </div>
        </div>

        <!-- Performance Tiles -->
        <div class="grid grid-cols-3 gap-3 shrink-0">
            <div class="action-card rounded-2xl p-3.5 text-white" style="background: linear-gradient(135deg, #10B981dd, #059669f2);">
                <div class="card-flare"></div>
                <span class="text-[9px] font-bold text-white/95 uppercase tracking-wider relative z-10">Dilayani</span>
                <p class="text-xl font-black mt-0.5 relative z-10 tabular-nums leading-none">{{ $stats['total_served'] }}</p>
            </div>
            <div class="action-card rounded-2xl p-3.5 text-white" style="background: linear-gradient(135deg, #EF4444dd, #DC2626f2);">
                <div class="card-flare"></div>
                <span class="text-[9px] font-bold text-white/95 uppercase tracking-wider relative z-10">Dilewati</span>
                <p class="text-xl font-black mt-0.5 relative z-10 tabular-nums leading-none">{{ $stats['total_skipped'] }}</p>
            </div>
            <div class="action-card rounded-2xl p-3.5 text-white" style="background: linear-gradient(135deg, #6366F1dd, #4F46E5f2);">
                <div class="card-flare"></div>
                <span class="text-[9px] font-bold text-white/95 uppercase tracking-wider relative z-10">Antre</span>
                <p class="text-xl font-black mt-0.5 relative z-10 tabular-nums leading-none">{{ $stats['waiting_count'] }}</p>
            </div>
        </div>

        <!-- Waiting List -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 flex flex-col overflow-hidden min-h-0 flex-1 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 shrink-0">
                <span class="text-xs font-bold text-slate-700">Daftar Tunggu</span>
                <span class="px-2 py-0.5 bg-slate-800 text-white text-[9px] font-bold rounded-md">{{ $stats['waiting_count'] }}</span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-2.5 custom-scrollbar pr-0.5 min-h-0">
                @forelse($waitingQueues as $q)
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-white hover:border-slate-200 transition duration-150">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center font-mono font-bold text-slate-700 shadow-sm border border-slate-200 text-xs">
                        {{ $q->queue_number }}
                    </div>
                    <div class="flex-1">
                        <span class="text-[10px] font-bold text-slate-600">{{ $q->created_at->format('H:i') }} WIB</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-350 mx-auto mb-2">
                        <i class="ti ti-mood-empty text-lg"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400">Tidak ada antrean</p>
                </div>
                @endforelse
            </div>
            
            @if($stats['waiting_count'] > 10)
            <p class="text-center mt-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest shrink-0">+ {{ $stats['waiting_count'] - 10 }} lagi</p>
            @endif
        </div>

    </div>
</div>

@if($activeQueue && $serviceTypes->count() > 0)
<!-- Transfer Modal -->
<div id="transferModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm transition-all">
    <div class="bg-white rounded-[2rem] p-8 w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center">
                    <i class="ti ti-arrows-exchange text-slate-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-800 tracking-tight">Transfer Antrean</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tiket {{ $activeQueue->queue_number }}</p>
                </div>
            </div>
            <button onclick="document.getElementById('transferModal').classList.add('hidden')" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition">
                <i class="ti ti-x text-sm"></i>
            </button>
        </div>
        
        <form action="{{ route('operator.transfer', $activeQueue) }}" method="POST">
            @csrf
            <div class="mb-6">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Pilih Layanan Tujuan</p>
                <div class="space-y-2 max-h-[200px] overflow-y-auto custom-scrollbar">
                    @foreach($serviceTypes as $st)
                    <label class="flex items-center gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer hover:bg-white hover:border-slate-200 transition group">
                        <input type="radio" name="service_type_id" value="{{ $st->id }}" class="w-4 h-4 text-slate-800 border-slate-300 focus:ring-slate-500" required @if($loop->first) checked @endif>
                        <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 transition">{{ $st->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="document.getElementById('transferModal').classList.add('hidden')" class="py-3 bg-slate-100 text-slate-500 font-bold rounded-xl hover:bg-slate-200 transition uppercase tracking-widest text-[10px]">Batal</button>
                <button type="submit" class="py-3 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition uppercase tracking-widest text-[10px]">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
    window.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel('queue-channel')
                .listen('.queue.called', (e) => {
                    console.log('Real-time notification: Queue Called', e);
                    window.location.reload();
                })
                .listen('.queue.created', (e) => {
                    console.log('Real-time notification: New Queue Created', e);
                    window.location.reload();
                });
        }
    });
</script>
@endpush
@endsection
