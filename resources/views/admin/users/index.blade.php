@extends('layouts.admin')

@section('title', 'User Management')
@section('header', 'Manage Users')

@section('content')
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    {{-- Table Header Info --}}
    <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-800">System Users</h3>
            <p class="text-xs text-slate-500 mt-1">Manage administrators and service operators</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-teal-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition flex items-center gap-2 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Add New User</span>
        </a>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                    <th class="px-8 py-5">User Information</th>
                    <th class="px-8 py-5 text-center">Role</th>
                    <th class="px-8 py-5 text-center">Institution</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 transition duration-200 group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-lg border border-slate-200 group-hover:bg-white group-hover:border-teal-200 transition duration-200 uppercase">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($user->role === 'admin' || $user->role === 'superadmin')
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider border border-indigo-100">
                                {{ $user->role }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 text-teal-600 text-[10px] font-bold uppercase tracking-wider border border-teal-100">
                                operator
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-xs font-bold text-slate-600 italic">
                            {{ $user->institution->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center justify-end gap-2 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white transition duration-200 shadow-sm border border-teal-100" title="Edit User">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            
                            @if($user->id !== auth()->id())
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition duration-200 shadow-sm border border-red-100" title="Delete User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @else
                            <div class="w-8 h-8 flex items-center justify-center text-slate-300 pointer-events-none" title="Cannot delete self">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <h3 class="text-slate-800 font-bold">No users found</h3>
                            <p class="text-slate-400 text-sm mt-1">Start by adding your administrative or operator staff.</p>
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
            title: 'Delete user?',
            text: `You are about to remove "${name}". This operator/admin will lose access immediately.`,
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
