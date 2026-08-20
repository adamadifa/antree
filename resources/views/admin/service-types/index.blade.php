@extends('layouts.admin')

@section('title', 'Service Types')
@section('header', 'Service Types Management')

@section('content')
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    {{-- Table Header Info --}}
    <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Available Services</h3>
            <p class="text-xs text-slate-500 mt-1">Manage and configure your queue categories</p>
        </div>
        <a href="{{ route('admin.service-types.create') }}" class="px-5 py-2.5 bg-teal-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition flex items-center gap-2 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Service</span>
        </a>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                    <th class="px-4 py-4 w-16 text-center">Ord</th>
                    <th class="px-4 py-4">Service Detail</th>
                    <th class="px-4 py-4 text-center">Avg. Time</th>
                    <th class="px-4 py-4 text-center">Status</th>
                    <th class="px-4 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($serviceTypes as $service)
                <tr class="hover:bg-slate-50/50 transition duration-200 group">
                    <td class="px-4 py-5">
                        <span class="text-xs font-bold text-slate-400">#{{ $service->sort_order }}</span>
                    </td>
                    <td class="px-4 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-sm" style="background-color: {{ $service->color }}">
                                {{ $service->code }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">{{ $service->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-tighter">Updated {{ $service->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-5 text-center">
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold border border-slate-200 uppercase tracking-widest">{{ $service->code }}</span>
                    </td>
                    <td class="px-4 py-5 text-center">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-lg border border-blue-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-xs font-bold">{{ $service->counters_count ?? 0 }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-5 text-center">
                        @if($service->is_active)
                        <span class="badge-status-active">Active</span>
                        @else
                        <span class="badge-status-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.service-types.edit', $service) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition duration-200 shadow-sm border border-blue-100" title="Edit Service">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form id="delete-form-{{ $service->id }}" action="{{ route('admin.service-types.destroy', $service) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete('{{ $service->id }}', '{{ $service->name }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition duration-200 shadow-sm border border-red-100" title="Delete Service">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">No Services Found</h4>
                            <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest">Start by adding a new service category</p>
                            <a href="{{ route('admin.service-types.create') }}" class="mt-4 px-6 py-2 bg-teal-500 text-white text-xs font-bold rounded-lg hover:bg-teal-600 transition shadow-md shadow-teal-500/10">Add First Service</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .badge-status-active {
        @apply inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-emerald-100;
    }
    .badge-status-inactive {
        @apply inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-400 rounded-full text-[10px] font-bold uppercase tracking-wider border border-slate-200;
    }
</style>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Layanan?',
            text: "Apakah Anda yakin ingin menghapus layanan '" + name + "'? Tindakan ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
