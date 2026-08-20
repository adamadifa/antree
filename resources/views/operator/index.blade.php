@extends('layouts.admin')

@section('title', 'Operator Console')
@section('header', 'Operator Console - ' . $counter->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Main Calling Area -->
    <div class="lg:col-span-8 space-y-8">
        
        <!-- Current Number Card -->
        <div class="bg-white rounded-[2.5rem] p-12 text-center shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-teal-400 to-emerald-500"></div>
            
            <div class="mb-6">
                <span class="px-4 py-1.5 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">Currently Serving</span>
            </div>

            @if($activeQueue)
                <h1 class="text-9xl font-black text-slate-900 tracking-tighter mb-4 tabular-nums">{{ $activeQueue->queue_number }}</h1>
                <div class="inline-flex items-center gap-2 px-6 py-2 bg-teal-50 text-teal-700 rounded-full mb-12 border border-teal-100/50">
                    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                    <span class="text-xs font-extrabold uppercase tracking-widest">{{ $activeQueue->serviceType->name }}</span>
                </div>

                <!-- Action Button Group -->
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <form action="{{ route('operator.recall', $activeQueue) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-8 py-4 bg-white text-slate-600 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition duration-300 uppercase tracking-widest text-[10px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5h12M9 3v2m1.042 15.671a1.5 1.5 0 001.916-1.916l-1.042-1.042-1.042 1.042zM4 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Recall ({{ $activeQueue->recall_count }})
                        </button>
                    </form>

                    <form action="{{ route('operator.complete', $activeQueue) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-10 py-5 bg-emerald-500 text-white font-black rounded-[1.5rem] shadow-xl shadow-emerald-200 hover:bg-emerald-600 transition duration-300 uppercase tracking-widest text-xs translate-y-[-4px] hover:translate-y-[-6px]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Finish Service
                        </button>
                    </form>

                    <button type="button" @if($serviceTypes->count() == 0) disabled @else onclick="document.getElementById('transferModal').classList.remove('hidden')" @endif class="flex items-center gap-3 px-8 py-4 bg-white text-blue-500 font-bold rounded-2xl border border-blue-50 hover:bg-blue-50 transition duration-300 uppercase tracking-widest text-[10px] {{ $serviceTypes->count() == 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        Transfer
                    </button>

                    <form action="{{ route('operator.skip', $activeQueue) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-8 py-4 bg-white text-red-500 font-bold rounded-2xl border border-red-50 hover:bg-red-50 transition duration-300 uppercase tracking-widest text-[10px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                            Skip
                        </button>
                    </form>
                </div>
            @else
                <div class="py-20">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-4xl font-black text-slate-300 tracking-tighter mb-4 uppercase">No Active Queue</h2>
                    <p class="text-slate-400 font-medium">Click "Call Next" to start serving customers</p>
                </div>
            @endif
        </div>

        <!-- Big Call Next Button -->
        <form action="{{ route('operator.call-next') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-slate-900 text-white p-10 rounded-[2.5rem] shadow-2xl shadow-slate-400/30 hover:bg-slate-800 transition-all duration-300 group flex items-center justify-between overflow-hidden relative">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 skew-x-[-20deg] translate-x-10 group-hover:translate-x-5 transition duration-700"></div>
                <div class="text-left relative z-10">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-2">Ready for next customer?</p>
                    <h2 class="text-4xl font-black tracking-tight">Call Next Attendee</h2>
                </div>
                <div class="w-20 h-20 bg-teal-400 rounded-3xl flex items-center justify-center shadow-lg shadow-teal-400/20 group-hover:scale-110 group-hover:rotate-12 transition duration-500 relative z-10">
                    <svg class="w-10 h-10 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </div>
            </button>
        </form>

    </div>

    <!-- Sidebar Stats & List -->
    <div class="lg:col-span-4 space-y-8">
        
        <!-- Stats Card -->
        <div class="bg-white rounded-[2rem] p-8 shadow-lg shadow-slate-200/50 border border-slate-50">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Today's Performance</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100/50">
                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Served</p>
                    <p class="text-2xl font-black text-emerald-900">{{ $stats['total_served'] }}</p>
                </div>
                <div class="bg-red-50/50 p-5 rounded-2xl border border-red-100/50">
                    <p class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-1">Skipped</p>
                    <p class="text-2xl font-black text-red-900">{{ $stats['total_skipped'] }}</p>
                </div>
            </div>
        </div>

        <!-- Waiting List -->
        <div class="bg-white rounded-[2rem] p-8 shadow-lg shadow-slate-200/50 border border-slate-50 overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Next in Line</h4>
                <span class="px-2.5 py-1 bg-slate-900 text-white text-[9px] font-black rounded-lg">{{ $stats['waiting_count'] }}</span>
            </div>

            <div class="space-y-3">
                @forelse($waitingQueues as $q)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-teal-200 hover:bg-white transition duration-200">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-black text-slate-800 shadow-sm group-hover:text-teal-500 transition">
                            {{ $q->queue_number }}
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Awaiting</p>
                            <p class="text-xs font-extrabold text-slate-700">{{ $q->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-xs font-bold text-slate-400 italic">Queue is currently empty</p>
                </div>
                @endforelse
            </div>
            
            @if($stats['waiting_count'] > 10)
            <p class="text-center mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">+ {{ $stats['waiting_count'] - 10 }} more waiting</p>
            @endif
        </div>

    </div>
</div>

@if($activeQueue && $serviceTypes->count() > 0)
<!-- Transfer Modal -->
<div id="transferModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="document.getElementById('transferModal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-10 bg-white rounded-[2.5rem] shadow-2xl border border-slate-100">
        <div class="mb-8">
            <h3 class="text-2xl font-black text-slate-900 mb-2">Transfer Antrean</h3>
            <p class="text-sm text-slate-500">Pilih layanan tujuan untuk memindahkan antrean <span class="font-black text-teal-600">{{ $activeQueue->queue_number }}</span>.</p>
        </div>
        
        <form action="{{ route('operator.transfer', $activeQueue) }}" method="POST">
            @csrf
            <div class="mb-10">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Layanan Tujuan</label>
                <div class="space-y-3">
                    @foreach($serviceTypes as $st)
                    <label class="relative flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 cursor-pointer hover:bg-white hover:border-teal-200 transition group">
                        <input type="radio" name="service_type_id" value="{{ $st->id }}" class="hidden peer" required @if($loop->first) checked @endif>
                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 mr-4 flex items-center justify-center peer-checked:border-teal-500 peer-checked:bg-teal-500 transition">
                            <div class="w-1.5 h-1.5 rounded-full bg-white scale-0 peer-checked:scale-100 transition"></div>
                        </div>
                        <span class="text-sm font-bold text-slate-700 group-hover:text-teal-600 transition">{{ $st->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="document.getElementById('transferModal').classList.add('hidden')" class="px-8 py-4 bg-slate-100 text-slate-500 font-bold rounded-2xl hover:bg-slate-200 transition uppercase tracking-widest text-[10px]">Batal</button>
                <button type="submit" class="px-8 py-4 bg-teal-500 text-white font-black rounded-2xl shadow-lg shadow-teal-200 hover:bg-teal-600 transition uppercase tracking-widest text-[10px]">Konfirmasi</button>
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
                    // Reload to sync stats and waiting list
                    window.location.reload();
                })
                .listen('.queue.created', (e) => {
                    console.log('Real-time notification: New Queue Created', e);
                    // Reload to update waiting list and total count
                    window.location.reload();
                });
        }
    });
</script>
@endpush
@endsection
