@extends('layouts.admin')

@section('title', 'Add New User')
@section('header', 'User Registration')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Form Header --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">New Account</h3>
                <p class="text-xs text-slate-500 mt-1">Register a new administrator or operator</p>
            </div>
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" id="user-form" class="p-8 space-y-6" novalidate>
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                {{-- Name --}}
                <div class="space-y-1.5">
                    <label for="name" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. John Doe" 
                           oninput="validateName(this.value)"
                           class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 @error('name') border-red-300 ring-4 ring-red-50 @enderror">
                    <p id="error-name" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Nama tidak boleh kosong.</p>
                    @error('name') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="user@antree.id" 
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
                            <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    {{-- Dummy Institution Info --}}
                    <div class="space-y-1.5 opacity-60">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Institution</label>
                        <div class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-500">
                             Antree HQ (Default)
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Password</label>
                        <input type="password" name="password" id="password" placeholder="Min. 8 characters" 
                               oninput="validatePassword()"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                        <p id="error-password" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Password minimal 8 karakter.</p>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password" 
                               oninput="validatePassword()"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                        <p id="error-password-confirm" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Password tidak cocok.</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="submit" id="btn-save" class="flex-1 h-12 bg-teal-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition duration-200">
                    Create Account
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-8 h-12 flex items-center justify-center border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    let isEmailValid = false;
    let timeout = null;

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
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(val)) {
            errorEl.textContent = 'Format email tidak valid.';
            errorEl.classList.remove('hidden');
            inputEl.classList.add('border-red-300', 'ring-4', 'ring-red-50');
            isEmailValid = false;
            return;
        }

        try {
            const response = await fetch(`{{ route('admin.users.check-email') }}?email=${val}`);
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
