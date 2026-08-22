@extends('layouts.admin')

@section('title', 'Add New User')
@section('header', 'User Registration')

@section('content')
<div class="max-w-3xl space-y-6">
    {{-- Breadcrumb Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Create User</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-655 transition">
                    <svg class="w-3.5 h-3.5 inline mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <span>/</span>
                <a href="{{ route('admin.users.index') }}" class="hover:text-slate-655 transition">Users</a>
                <span>/</span>
                <span class="text-rose-500 font-semibold">Create</span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/85 shadow-sm overflow-hidden flex flex-col">
        {{-- Header Section --}}
        <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">New Account</h3>
                <p class="text-[11px] text-slate-400 font-medium">Register a new administrator or operator.</p>
            </div>
            <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
        </div>

        <div class="p-8">
            <form id="user-form" action="{{ route('admin.users.store') }}" method="POST" class="space-y-6" novalidate>
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-7">
                    {{-- Name --}}
                    <div class="space-y-1 md:col-span-2">
                        <div class="relative">
                            <label for="name" id="label-name" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('name') text-red-500 @enderror">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-name" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('name') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-450 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. John Doe" 
                                       oninput="validateName(this.value)"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-name" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('name') block @else hidden @enderror">
                            @error('name') {{ $message }} @else Nama tidak boleh kosong @enderror
                        </p>
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1 md:col-span-2">
                        <div class="relative">
                            <label for="email" id="label-email" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('email') text-red-500 @enderror">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-email" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('email') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-450 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="user@antree.id" 
                                       oninput="debouncedValidateEmail(this.value)"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-email" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('email') block @else hidden @enderror">
                            @error('email') {{ $message }} @else Email tidak valid atau sudah digunakan @enderror
                        </p>
                    </div>

                    {{-- Role --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="role" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all">
                                Access Role <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:border-rose-450 focus-within:ring-4 focus-within:ring-rose-50">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a5 5 0 11-10 0 5 5 0 0110 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2 2 4-4"/>
                                </svg>
                                <select name="role" id="role"
                                        class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 cursor-pointer">
                                    <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Institution Dummy Info --}}
                    <div class="space-y-1">
                        <div class="relative opacity-70">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-400">
                                Institution
                            </label>
                            <div class="flex items-center border border-slate-200 bg-slate-50/50 rounded-2xl px-4 py-1.5">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <div class="w-full bg-transparent text-sm font-medium text-slate-500 py-2">
                                     Antree HQ (Default)
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="password" id="label-password" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-password" class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <input type="password" name="password" id="password" placeholder="Min. 8 characters" 
                                       oninput="validatePassword()"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-password" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize hidden">Password minimal 8 karakter</p>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="password_confirmation" id="label-password_confirmation" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-password_confirmation" class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password" 
                                       oninput="validatePassword()"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-password-confirm" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize hidden">Password tidak cocok</p>
                    </div>
                </div>

                <div class="pt-6 flex items-center gap-3 border-t border-slate-100">
                    <button type="submit" id="btn-save" class="flex-1 h-11 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/10 transition-all duration-150 uppercase tracking-wider">
                        Create Account
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-6 h-11 flex items-center justify-center border border-slate-200 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition duration-150 uppercase tracking-wider">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let isNameValid = true;
    let isEmailValid = false;
    let isPassValid = false;
    let timeout = null;

    function validateName(val) {
        const wrapper = document.getElementById('wrapper-name');
        const label = document.getElementById('label-name');
        const error = document.getElementById('error-name');
        if (val.trim() === '') {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.classList.remove('hidden');
            isNameValid = false;
        } else {
            wrapper.classList.add('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.remove('text-red-500');
            error.classList.add('hidden');
            isNameValid = true;
        }
    }

    function debouncedValidateEmail(val) {
        clearTimeout(timeout);
        timeout = setTimeout(() => validateEmail(val), 500);
    }

    async function validateEmail(val) {
        const wrapper = document.getElementById('wrapper-email');
        const label = document.getElementById('label-email');
        const error = document.getElementById('error-email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (val.trim() === '') {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.textContent = 'Email wajib diisi';
            error.classList.remove('hidden');
            isEmailValid = false;
            return;
        }

        if (!emailRegex.test(val)) {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.textContent = 'Format email tidak valid';
            error.classList.remove('hidden');
            isEmailValid = false;
            return;
        }

        // Reset styling while checking
        wrapper.classList.add('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
        wrapper.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
        label.classList.remove('text-red-500');
        error.classList.add('hidden');
        isEmailValid = true;

        try {
            const response = await fetch(`{{ route('admin.users.check-email') }}?email=${val}`);
            const data = await response.json();
            
            if (data.exists) {
                wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
                wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
                label.classList.add('text-red-500');
                error.textContent = 'Email sudah digunakan';
                error.classList.remove('hidden');
                isEmailValid = false;
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
        const wrapperPass = document.getElementById('wrapper-password');
        const wrapperConfirm = document.getElementById('wrapper-password_confirmation');
        const labelPass = document.getElementById('label-password');
        const labelConfirm = document.getElementById('label-password_confirmation');

        let isValid = true;

        if (pass.length < 8) {
            wrapperPass.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapperPass.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            labelPass.classList.add('text-red-500');
            errorPass.classList.remove('hidden');
            isValid = false;
        } else {
            wrapperPass.classList.add('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapperPass.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            labelPass.classList.remove('text-red-500');
            errorPass.classList.add('hidden');
        }

        if (pass !== confirm || confirm.length === 0) {
            wrapperConfirm.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapperConfirm.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            labelConfirm.classList.add('text-red-500');
            errorConfirm.classList.remove('hidden');
            isValid = false;
        } else {
            wrapperConfirm.classList.add('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapperConfirm.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            labelConfirm.classList.remove('text-red-500');
            errorConfirm.classList.add('hidden');
        }

        isPassValid = isValid;
        return isValid;
    }

    document.getElementById('user-form').addEventListener('submit', function(e) {
        validateName(document.getElementById('name').value);
        validatePassword();
        
        // Also run email validation check directly to ensure state is caught if they submit blank
        const emailVal = document.getElementById('email').value;
        if (emailVal.trim() === '') {
            validateEmail(emailVal);
        }

        if (!isNameValid || !isEmailValid || !isPassValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data tidak valid',
                text: 'Silakan periksa kembali form Anda.',
                confirmButtonColor: '#EF4444',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold text-white'
                }
            });
        }
    });
</script>
@endsection
