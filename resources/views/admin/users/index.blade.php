@extends('layouts.admin')

@section('title', 'User Management')
@section('header', 'Manage Users')

@section('content')
<div class="space-y-6">
    {{-- Header & Action Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">System Users</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-655 transition">
                    <svg class="w-3.5 h-3.5 inline mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <span>/</span>
                <span>User Management</span>
                <span>/</span>
                <span class="text-rose-500 font-semibold">Users</span>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition-all duration-150 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add User</span>
            </a>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Users & Staff Accounts</h3>
                <p class="text-[11px] text-slate-400 font-medium">Manage administrators and service operators access rights.</p>
            </div>
            <span class="px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-bold border border-rose-100 uppercase tracking-wider">
                Total: {{ count($users) }} Users
            </span>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3">User Information</th>
                        <th class="px-6 py-3 text-center">Role</th>
                        <th class="px-6 py-3 text-center">Institution</th>
                        <th class="px-6 py-3 text-right pr-8">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        {{-- User Information --}}
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm border border-slate-200 uppercase shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 truncate">{{ $user->name }}</p>
                                    <p class="text-[9px] font-medium text-slate-400 mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- Role --}}
                        <td class="px-6 py-3.5 text-center">
                            @if($user->role === 'admin' || $user->role === 'superadmin')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-rose-50 text-rose-650 rounded-full text-[9px] font-bold uppercase tracking-wider border border-rose-100">
                                    {{ $user->role }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-500 rounded-full text-[9px] font-bold uppercase tracking-wider border border-slate-250">
                                    operator
                                </span>
                            @endif
                        </td>
                        {{-- Institution --}}
                        <td class="px-6 py-3.5 text-center">
                            <span class="text-xs font-semibold text-slate-500 italic">
                                {{ $user->institution->name ?? 'N/A' }}
                            </span>
                        </td>
                        {{-- Actions --}}
                        <td class="px-6 py-3.5 text-right pr-8">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.edit', $user) }}" class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-slate-650 hover:bg-rose-50 hover:text-rose-600 transition duration-150 border border-slate-200/50" title="Edit User">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                
                                @if($user->id !== auth()->id())
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-red-500 hover:bg-red-50 hover:text-red-655 transition duration-150 border border-slate-200/50" title="Delete User">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @else
                                <div class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none" title="Cannot delete self">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <h4 class="text-xs font-bold text-slate-700">No Users Found</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Start by adding your administrative or operator staff.</p>
                                <a href="{{ route('admin.users.create') }}" class="mt-3.5 px-4 py-1.5 bg-rose-500 text-white text-[10px] font-bold rounded-lg hover:bg-rose-600 transition shadow-sm">Add First User</a>
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
            title: 'Hapus User?',
            text: "Apakah Anda yakin ingin menghapus user '" + name + "'? Akses akun ini akan dicabut segera.",
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
