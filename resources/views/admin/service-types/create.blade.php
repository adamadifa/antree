@extends('layouts.admin')

@section('title', 'Create Service Type')
@section('header', 'Create New Service')

@section('content')
<div class="max-w-3xl space-y-6">
    {{-- Breadcrumb Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Create Service Type</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-655 transition">
                    <svg class="w-3.5 h-3.5 inline mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <span>/</span>
                <a href="{{ route('admin.service-types.index') }}" class="hover:text-slate-655 transition">Service Types</a>
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
                <h3 class="text-sm font-bold text-slate-800">Service Configuration</h3>
                <p class="text-[11px] text-slate-400 font-medium">Define settings for your new queue category.</p>
            </div>
            <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            </div>
        </div>

        <div class="p-8">
            <form id="service-form" action="{{ route('admin.service-types.store') }}" method="POST" class="space-y-6" novalidate>
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-7">
                    {{-- Name --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="name" id="label-name" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('name') text-red-500 @enderror">
                                Service Name <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-name" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('name') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-400 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukan nama layanan" 
                                       oninput="validateName(this.value)"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-name" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('name') block @else hidden @enderror">
                            @error('name') {{ $message }} @else Nama lengkap wajib diisi @enderror
                        </p>
                    </div>

                    {{-- Code --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label for="code" id="label-code" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('code') text-red-500 @enderror">
                                Service Code (Prefix) <span class="text-red-500">*</span>
                            </label>
                            <div id="wrapper-code" class="flex items-center border rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:ring-4 @error('code') border-red-300 ring-red-50 focus-within:border-red-400 focus-within:ring-red-50 @else border-slate-200 focus-within:border-rose-450 focus-within:ring-rose-50 @enderror">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="Contoh: A, CS, T" 
                                       oninput="validateCode(this.value)"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                        <p id="error-code" class="text-[10px] font-semibold text-red-500 mt-1.5 pl-1 capitalize @error('code') block @else hidden @enderror">
                            @error('code') {{ $message }} @else Kode layanan wajib diisi @enderror
                        </p>
                    </div>

                    {{-- Color Picker & Presets --}}
                    <div class="space-y-1 md:col-span-2">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all @error('color') text-red-500 @enderror">
                                Identity Color <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-wrap items-center gap-4 p-4 bg-white border border-slate-200 rounded-2xl">
                                {{-- Color Presets --}}
                                <div class="flex items-center gap-2 pr-4 border-r border-slate-200">
                                    @php $presets = ['#2DD4BF', '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899', '#F43F5E', '#F16032', '#FAB005', '#82C91E']; @endphp
                                    @foreach($presets as $preset)
                                    <button type="button" 
                                            onclick="updateColor('{{ $preset }}')"
                                            class="w-6 h-6 rounded-full border-2 border-white shadow-sm hover:scale-125 transition duration-200" 
                                            style="background-color: {{ $preset }}"></button>
                                    @endforeach
                                </div>

                                {{-- Native Picker & Hex Input --}}
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="relative w-9 h-9 shrink-0">
                                        <input type="color" id="color-picker" value="{{ old('color', '#2DD4BF') }}" 
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                               oninput="updateColor(this.value)">
                                        <div id="color-preview" class="w-full h-full rounded-xl border border-white shadow-sm ring-1 ring-slate-250" style="background-color: {{ old('color', '#2DD4BF') }}"></div>
                                    </div>
                                    <div class="flex-1 relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">#</span>
                                        <input type="text" name="color" id="color-text" value="{{ old('color', '#2DD4BF') }}" 
                                               class="w-full pl-8 pr-4 py-2 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-rose-50 focus:border-rose-500 transition duration-200 text-xs font-mono font-bold text-slate-705 uppercase"
                                               oninput="if(this.value.startsWith('#')) { updateColor(this.value); } else { updateColor('#' + this.value); }">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('color') <p class="text-[10px] font-semibold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sort Order --}}
                    <div class="space-y-1 col-span-2 md:col-span-1">
                        <div class="relative">
                            <label for="sort_order" class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500 transition-all">
                                Display Order <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition duration-200 focus-within:border-rose-450 focus-within:ring-4 focus-within:ring-rose-50">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                                </svg>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" placeholder="Masukkan nomor urut" 
                                       class="w-full bg-transparent text-sm font-medium text-slate-755 border-0 focus:ring-0 outline-none py-2" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Toggle --}}
                <div class="py-5 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-750">Display this service?</h4>
                        <p class="text-[11px] text-slate-450 font-medium">If disabled, this service category will not appear on Kiosk.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="is_active" class="sr-only peer" checked value="1">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                    </label>
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button type="submit" id="btn-save" class="flex-1 h-11 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/10 transition-all duration-150 uppercase tracking-wider">
                        Save Service
                    </button>
                    <a href="{{ route('admin.service-types.index') }}" class="px-6 h-11 flex items-center justify-center border border-slate-200 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition duration-150 uppercase tracking-wider">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let codeTimeout = null;
    let isNameValid = true;
    let isCodeValid = true;

    // Form Submit Handler
    document.getElementById('service-form').addEventListener('submit', function(e) {
        const nameValue = document.getElementById('name').value;
        const codeValue = document.getElementById('code').value;
        
        validateName(nameValue);
        validateCode(codeValue);

        if (!isNameValid || !isCodeValid) {
            e.preventDefault();
            // Scroll to the first error
            const firstError = document.querySelector('.border-red-300');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    function updateColor(value) {
        const hex = value.startsWith('#') ? value : '#' + value;
        document.getElementById('color-preview').style.backgroundColor = hex;
        document.getElementById('color-text').value = hex.toUpperCase();
        document.getElementById('color-picker').value = hex;
    }

    function validateName(value) {
        const wrapper = document.getElementById('wrapper-name');
        const label = document.getElementById('label-name');
        const error = document.getElementById('error-name');
        
        if (value.trim() === '') {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-450', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.innerText = 'Nama layanan wajib diisi';
            error.classList.remove('hidden');
            isNameValid = false;
        } else {
            wrapper.classList.add('border-slate-200', 'focus-within:border-rose-450', 'focus-within:ring-rose-50');
            wrapper.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.remove('text-red-500');
            error.classList.add('hidden');
            isNameValid = true;
        }
    }

    function validateCode(value) {
        const wrapper = document.getElementById('wrapper-code');
        const label = document.getElementById('label-code');
        const error = document.getElementById('error-code');
        
        if (value.trim() === '') {
            wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-450', 'focus-within:ring-rose-50');
            wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
            label.classList.add('text-red-500');
            error.innerText = 'Kode layanan wajib diisi';
            error.classList.remove('hidden');
            isCodeValid = false;
            return;
        }

        // Immediately hide error as it is non-empty
        wrapper.classList.add('border-slate-200', 'focus-within:border-rose-450', 'focus-within:ring-rose-50');
        wrapper.classList.remove('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
        label.classList.remove('text-red-500');
        error.classList.add('hidden');
        isCodeValid = true;

        // AJAX Uniqueness Check (Debounced)
        clearTimeout(codeTimeout);
        codeTimeout = setTimeout(() => {
            fetch('{{ route('admin.service-types.check-code') }}?code=' + encodeURIComponent(value))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        wrapper.classList.remove('border-slate-200', 'focus-within:border-rose-450', 'focus-within:ring-rose-50');
                        wrapper.classList.add('border-red-300', 'ring-red-50', 'focus-within:border-red-400', 'focus-within:ring-red-50');
                        label.classList.add('text-red-500');
                        error.innerText = 'Kode layanan "' + value + '" sudah digunakan';
                        error.classList.remove('hidden');
                        isCodeValid = false;
                    }
                });
        }, 500);
    }
</script>
@endsection
