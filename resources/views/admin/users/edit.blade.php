@extends('layouts.admin')

@section('title', 'Edit User')
@section('header', 'Modify Account')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Form Header --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Edit: {{ $user->name }}</h3>
                <p class="text-xs text-slate-500 mt-1">Update profile information and access rights</p>
            </div>
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" id="user-form" class="p-8 space-y-6" novalidate>
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6">
                {{-- Name --}}
                <div class="space-y-1.5">
                    <label for="name" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" placeholder="e.g. John Doe" 
                           oninput="validateName(this.value)"
                           class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 @error('name') border-red-300 ring-4 ring-red-50 @enderror">
                    <p id="error-name" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Nama tidak boleh kosong.</p>
                    @error('name') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="user@antree.id" 
                           oninput="debouncedValidateEmail(this.value)"
                           class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 @error('email') border-red-300 ring-4 ring-red-50 @enderror">
                    <p id="error-email" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Email tidak valid atau sudah digunakan.</p>
                    @error('email') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Role --}}
                    <div class="space-y-1.5">
                        <label for="role" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Access Role</label>
                        <select name="role" id="role" 
                                class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                            <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    {{-- Dummy Institution Info --}}
                    <div class="space-y-1.5 opacity-60">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Institution</label>
                        <div class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-500">
                             {{ $user->institution->name ?? 'Antree HQ' }}
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100 mb-2">
                    <p class="text-xs text-amber-700 font-medium">Kosongkan password jika tidak ingin mengubahnya.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">New Password</label>
                        <input type="password" name="password" id="password" placeholder="Leave blank to keep current" 
                               oninput="validatePassword()"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                        <p id="error-password" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Password minimal 8 karakter.</p>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat new password" 
                               oninput="validatePassword()"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                        <p id="error-password-confirm" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Password tidak cocok.</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="submit" id="btn-save" class="flex-1 h-12 bg-teal-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition duration-200">
                    Update Account
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-8 h-12 flex items-center justify-center border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    let isEmailValid = true;
    let timeout = null;
    const currentEmail = "{{ $user->email }}";

    function validateName(val) {
        const errorEl = document.getElementById('error-name');
        const inputEl = document.getElementById('name');
        if (val.trim() === '') {
            errorEl.classList.remove('hidden');
            inputEl.classList.add('border-red-300', 'ring-4', 'ring-red-50');
            return false;
        } else {
            errorEl.classList.add('hidden');
            inputEl.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
            return true;
        }
    }

    function debouncedValidateEmail(val) {
        clearTimeout(timeout);
        timeout = setTimeout(() => validateEmail(val), 500);
    }

    async function validateEmail(val) {
        const errorEl = document.getElementById('error-email');
        const inputEl = document.getElementById('email');
        
        if (val === currentEmail) {
            errorEl.classList.add('hidden');
            inputEl.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
            isEmailValid = true;
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(val)) {
            errorEl.textContent = 'Format email tidak valid.';
            errorEl.classList.remove('hidden');
            inputEl.classList.add('border-red-300', 'ring-4', 'ring-red-50');
            isEmailValid = false;
            return;
        }

        try {
            const response = await fetch(`{{ route('admin.users.check-email') }}?email=${val}&exclude_id={{ $user->id }}`);
            const data = await response.json();
            
            if (data.exists) {
                errorEl.textContent = 'Email sudah digunakan.';
                errorEl.classList.remove('hidden');
                inputEl.classList.add('border-red-300', 'ring-4', 'ring-red-50');
                isEmailValid = false;
            } else {
                errorEl.classList.add('hidden');
                inputEl.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
                isEmailValid = true;
            }
        } catch (error) {
            console.error('Error validation:', error);
        }
    }

    function validatePassword() {
        const pass = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const errorPass = document.getElementById('error-password');
        const errorConfirm = document.getElementById('error-password-confirm');
        const inputPass = document.getElementById('password');
        const inputConfirm = document.getElementById('password_confirmation');

        let isValid = true;

        if (pass.length > 0) {
            if (pass.length < 8) {
                errorPass.classList.remove('hidden');
                inputPass.classList.add('border-red-300', 'ring-4', 'ring-red-50');
                isValid = false;
            } else {
                errorPass.classList.add('hidden');
                inputPass.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
            }

            if (pass !== confirm) {
                errorConfirm.classList.remove('hidden');
                inputConfirm.classList.add('border-red-300', 'ring-4', 'ring-red-50');
                isValid = false;
            } else {
                errorConfirm.classList.add('hidden');
                inputConfirm.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
            }
        } else {
            errorPass.classList.add('hidden');
            errorConfirm.classList.add('hidden');
            inputPass.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
            inputConfirm.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
        }

        return isValid;
    }

    document.getElementById('user-form').addEventListener('submit', function(e) {
        const isNameValid = validateName(document.getElementById('name').value);
        const isPassValid = validatePassword();
        
        if (!isNameValid || !isEmailValid || !isPassValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data tidak valid',
                text: 'Silakan periksa kembali form Anda.',
                confirmButtonColor: '#0D9488'
            });
        }
    });
</script>
@endsection
