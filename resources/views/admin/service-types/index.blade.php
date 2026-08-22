@extends('layouts.admin')

@section('title', 'Service Types')
@section('header', 'Service Types Management')

@section('content')
<div class="space-y-6">
    {{-- Header & Action Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Service Types</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-650 transition">
                    <svg class="w-3.5 h-3.5 inline mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <span>/</span>
                <span>Queue Management</span>
                <span>/</span>
                <span class="text-rose-500 font-semibold">Service Types</span>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.service-types.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition-all duration-150 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Service</span>
            </a>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Queue Service Categories</h3>
                <p class="text-[11px] text-slate-400 font-medium">Configure list of available services and queue settings.</p>
            </div>
            <span class="px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-bold border border-rose-100 uppercase tracking-wider">
                Total: {{ count($serviceTypes) }} Services
            </span>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3 w-16 text-center">Order</th>
                        <th class="px-6 py-3">Service Name</th>
                        <th class="px-6 py-3 text-center">Code Prefix</th>
                        <th class="px-6 py-3 text-center">Counters</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right pr-8">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($serviceTypes as $service)
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        {{-- Sort Order --}}
                        <td class="px-6 py-3.5 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-slate-100 text-[10px] font-bold text-slate-500">
                                #{{ $service->sort_order }}
                            </span>
                        </td>
                        {{-- Service Details --}}
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm shrink-0" style="background-color: {{ $service->color }}">
                                    {{ $service->code }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 truncate">{{ $service->name }}</p>
                                    <p class="text-[9px] font-medium text-slate-400 mt-0.5">Updated {{ $service->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- Code --}}
                        <td class="px-6 py-3.5 text-center">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-650 rounded border border-slate-200 text-[10px] font-mono font-bold uppercase tracking-wider">
                                {{ $service->code }}
                            </span>
                        </td>
                        {{-- Counters --}}
                        <td class="px-6 py-3.5 text-center">
                            <div class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-600 rounded-lg border border-rose-100/50 text-[11px] font-bold">
                                <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>{{ $service->counters_count ?? 0 }}</span>
                            </div>
                        </td>
                        {{-- Status --}}
                        <td class="px-6 py-3.5 text-center">
                            @if($service->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-bold uppercase tracking-wider border border-emerald-100/60">Active</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-400 rounded-full text-[9px] font-bold uppercase tracking-wider border border-slate-250">Inactive</span>
                            @endif
                        </td>
                        {{-- Actions --}}
                        <td class="px-6 py-3.5 text-right pr-8">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.service-types.edit', $service) }}" class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-slate-650 hover:bg-rose-50 hover:text-rose-600 transition duration-150 border border-slate-200/50" title="Edit Service">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form id="delete-form-{{ $service->id }}" action="{{ route('admin.service-types.destroy', $service) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete('{{ $service->id }}', '{{ $service->name }}')"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-red-500 hover:bg-red-50 hover:text-red-650 transition duration-150 border border-slate-200/50" title="Delete Service">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-slate-305" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <h4 class="text-xs font-bold text-slate-700">No Services Found</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Start by adding a new service category.</p>
                                <a href="{{ route('admin.service-types.create') }}" class="mt-3.5 px-4 py-1.5 bg-rose-500 text-white text-[10px] font-bold rounded-lg hover:bg-rose-600 transition shadow-sm">Add First Service</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Layanan?',
            text: "Apakah Anda yakin ingin menghapus layanan '" + name + "'? Tindakan ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold text-white',
                cancelButton: 'px-5 py-2.5 rounded-xl text-xs font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
