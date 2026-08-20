@extends('layouts.admin')

@section('title', 'Service Counters')
@section('header', 'Manage Counters')

@section('content')
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    {{-- Table Header Info --}}
    <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Counters & Operators</h3>
            <p class="text-xs text-slate-500 mt-1">Configure your service points and assign operators</p>
        </div>
        <a href="{{ route('admin.counters.create') }}" class="px-5 py-2.5 bg-teal-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition flex items-center gap-2 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Counter</span>
        </a>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                    <th class="px-8 py-5 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Counter Detail</th>
                    <th class="px-8 py-5 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Number</th>
                    <th class="px-8 py-5 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Service Category</th>
                    <th class="px-8 py-5 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-5 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($counters as $counter)
                    <tr class="hover:bg-slate-50/50 transition duration-200 group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-lg border border-slate-200 group-hover:bg-white group-hover:border-teal-200 transition duration-200">
                                    {{ substr($counter->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800">{{ $counter->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium lowercase italic">
                                        Assigned to: 
                                        <span class="text-slate-600 uppercase font-bold not-italic">
                                            {{ $counter->operator->name ?? 'No Operator Assigned' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 text-blue-600 font-bold text-sm border border-blue-100 shadow-sm">
                                {{ str_pad($counter->number, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $counter->serviceType->color ?? '#CBD5E1' }}"></div>
                                <span class="text-sm font-bold text-slate-700">{{ $counter->serviceType->name ?? 'Unassigned' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($counter->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider border border-emerald-100">Active</span>
                            @elseif($counter->status === 'maintenance')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-100">Maintenance</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">Inactive</span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2 text-right">
                                <a href="{{ route('admin.counters.edit', $counter) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white transition duration-200 shadow-sm border border-teal-100" title="Edit Counter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form id="delete-form-{{ $counter->id }}" action="{{ route('admin.counters.destroy', $counter) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete('{{ $counter->id }}', '{{ $counter->name }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition duration-200 shadow-sm border border-red-100" title="Delete Counter">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <h3 class="text-slate-800 font-bold">No counters found</h3>
                                <p class="text-slate-400 text-sm mt-1">Start by adding your first service counter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Delete counter?',
            text: `You are about to remove "${name}". This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            padding: '2rem',
            background: '#ffffff',
            borderRadius: '1.5rem',
            customClass: {
                title: 'text-2xl font-bold text-slate-800',
                htmlContainer: 'text-slate-500',
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
