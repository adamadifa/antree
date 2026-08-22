@extends('layouts.admin')

@section('title', 'Edit Counter')
@section('header', 'Update Counter Configuration')

@section('content')
<div class="max-w-3xl space-y-6">
    {{-- Breadcrumb Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Edit Counter</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-655 transition">
                    <svg class="w-3.5 h-3.5 inline mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <span>/</span>
                <a href="{{ route('admin.counters.index') }}" class="hover:text-slate-655 transition">Counters</a>
                <span>/</span>
                <span class="text-rose-500 font-semibold">Edit</span>
            </div>
        </div>
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-700 bg-slate-100 font-bold text-sm border border-slate-205 shadow-sm shrink-0">
            {{ str_pad($counter->number, 2, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        {{-- Header Section --}}
        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Updating Counter: {{ $counter->name }}</h3>
                <p class="text-[11px] text-slate-400 font-medium">Modify settings and operator assignments.</p>
            </div>
            <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>

        <div class="p-6">
            <form id="service-form" action="{{ route('admin.counters.update', $counter) }}" method="POST" class="space-y-5" novalidate>
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Counter Name --}}
                    <div class="space-y-1 md:col-span-2">
                        <div class="relative">
                            <label for="name" id="label-name" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('name') text-red-500 @enderror">
                                Counter Display Name <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-name" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('name') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-450 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <input type="text" name="name" id="name" value="{{ old('name', $counter->name) }}" placeholder="e.g. Loket 1, Counter A" 
                                       oninput="validateName(this.value)"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-name" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('name') block @else hidden @enderror">
                            @error('name') {{ $message }} @else Nama loket wajib diisi @enderror
                        </p>
                    </div>

                    {{-- Counter Number --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="number" id="label-number" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('number') text-red-500 @enderror">
                                Counter Number <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-number" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('number') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-450 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                <input type="number" name="number" id="number" value="{{ old('number', $counter->number) }}" placeholder="e.g. 1, 2, 3" 
                                       oninput="validateNumber(this.value)"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-number" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('number') block @else hidden @enderror">
                            @error('number') {{ $message }} @else Nomor loket wajib diisi @enderror
                        </p>
                    </div>

                    {{-- Service Category --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="service_type_id" id="label-service_type_id" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('service_type_id') text-red-500 @enderror">
                                Service Category <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-service_type_id" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('service_type_id') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-450 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <select name="service_type_id" id="service_type_id" onchange="validateSelect('service_type_id', this.value)"
                                        class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 cursor-pointer">
                                    <option value="">Select Category</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('service_type_id', $counter->service_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p id="error-service_type_id" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('service_type_id') block @else hidden @enderror">
                            @error('service_type_id') {{ $message }} @else Kategori layanan wajib dipilih @enderror
                        </p>
                    </div>

                    {{-- Operator --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="user_id" id="label-user_id" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('user_id') text-red-500 @enderror">
                                Assigned Operator <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-user_id" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('user_id') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-455 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <select name="user_id" id="user_id" onchange="validateSelect('user_id', this.value)"
                                        class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 cursor-pointer">
                                    <option value="">Select Operator</option>
                                    @foreach($operators as $operator)
                                        <option value="{{ $operator->id }}" {{ old('user_id', $counter->user_id) == $operator->id ? 'selected' : '' }}>{{ $operator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p id="error-user_id" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('user_id') block @else hidden @enderror">
                            @error('user_id') {{ $message }} @else Operator wajib ditunjuk @enderror
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="status" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all">
                                Counter Status <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:border-rose-450 focus-within:ring-4 focus-within:ring-rose-50">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <select name="status" id="status"
                                        class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 cursor-pointer">
                                    <option value="active" {{ old('status', $counter->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('status', $counter->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="inactive" {{ old('status', $counter->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center gap-3 border-t border-slate-100">
                    <button type="submit" id="btn-save" class="flex-1 h-11 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/10 transition-all duration-150 uppercase tracking-wider">
                        Update Configuration
                    </button>
                    <a href="{{ route('admin.counters.index') }}" class="px-6 h-11 flex items-center justify-center border border-slate-200 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition duration-150 uppercase tracking-wider">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let numberTimeout = null;
    let isNameValid = true;
    let isNumberValid = true;
    let isServiceTypeValid = true;
    let isUserValid = true;

    // Form Submit Handler
    document.getElementById('service-form').addEventListener('submit', function(e) {
        const nameValue = document.getElementById('name').value;
        const numberValue = document.getElementById('number').value;
        const serviceTypeValue = document.getElementById('service_type_id').value;
        const operatorValue = document.getElementById('user_id').value;
        
        validateName(nameValue);
        validateNumber(numberValue);
        validateSelect('service_type_id', serviceTypeValue);
        validateSelect('user_id', operatorValue);

        if (!isNameValid || !isNumberValid || !isServiceTypeValid || !isUserValid) {
            e.preventDefault();
            const firstError = document.querySelector('.border-red-300');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    function validateName(value) {
        const wrapper = document.getElementById('wrapper-name');
        const label = document.getElementById('label-name');
        const error = document.getElementById('error-name');
        
        if (value.trim() === '') {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.innerText = 'Nama loket wajib diisi';
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

    function validateSelect(id, value) {
        const wrapper = document.getElementById('wrapper-' + id);
        const label = document.getElementById('label-' + id);
        const error = document.getElementById('error-' + id);
        
        if (value === '') {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.classList.remove('hidden');
            if (id === 'service_type_id') isServiceTypeValid = false;
            if (id === 'user_id') isUserValid = false;
        } else {
            wrapper.classList.add('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.remove('text-red-500');
            error.classList.add('hidden');
            if (id === 'service_type_id') isServiceTypeValid = true;
            if (id === 'user_id') isUserValid = true;
        }
    }

    function validateNumber(value) {
        const wrapper = document.getElementById('wrapper-number');
        const label = document.getElementById('label-number');
        const error = document.getElementById('error-number');
        
        if (value.trim() === '') {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.innerText = 'Nomor loket wajib diisi';
            error.classList.remove('hidden');
            isNumberValid = false;
            return;
        }

        wrapper.classList.add('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
        wrapper.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
        label.classList.remove('text-red-500');
        error.classList.add('hidden');
        isNumberValid = true;

        // AJAX Uniqueness Check (Debounced)
        clearTimeout(numberTimeout);
        numberTimeout = setTimeout(() => {
            fetch('{{ route('admin.counters.check-number') }}?number=' + encodeURIComponent(value) + '&exclude_id={{ $counter->id }}')
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-455', 'focus-within:ring-rose-50');
                        wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
                        label.classList.add('text-red-500');
                        error.innerText = 'Nomor loket "' + value + '" sudah terdaftar';
                        error.classList.remove('hidden');
                        isNumberValid = false;
                    }
                });
        }, 500);
    }
</script>
@endsection
