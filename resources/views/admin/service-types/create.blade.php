@extends('layouts.admin')

@section('title', 'Create Service Type')
@section('header', 'Create New Service')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden min-h-[500px] flex flex-col">
        {{-- Header Section --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Service Configuration</h3>
                <p class="text-xs text-slate-500 mt-1">Define settings for your new queue category</p>
            </div>
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            </div>
        </div>

        <div class="p-8 flex-1">
            <form id="service-form" action="{{ route('admin.service-types.store') }}" method="POST" class="space-y-6" novalidate>
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div class="space-y-1.5">
                        <label for="name" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Service Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Customer Service" 
                               oninput="validateName(this.value)"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 @error('name') border-red-300 ring-4 ring-red-50 @enderror">
                        <p id="error-name" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Nama layanan tidak boleh kosong.</p>
                        @error('name') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>

                    {{-- Code --}}
                    <div class="space-y-1.5">
                        <label for="code" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Service Code (Prefix)</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="e.g. A, CS, T" 
                               oninput="validateCode(this.value)"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 @error('code') border-red-300 ring-4 ring-red-50 @enderror">
                        <p id="error-code" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Kode layanan sudah digunakan.</p>
                        @error('code') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>
{{-- ... partial content omitted for brevity, but I will fulfill the full replacement below --}}


                    {{-- Color --}}
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="color" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Identity Color</label>
                        <div class="flex flex-wrap items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
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
                                <div class="relative w-10 h-10 shrink-0">
                                    <input type="color" id="color-picker" value="{{ old('color', '#2DD4BF') }}" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           oninput="updateColor(this.value)">
                                    <div id="color-preview" class="w-full h-full rounded-xl border border-white shadow-sm ring-1 ring-slate-200" style="background-color: {{ old('color', '#2DD4BF') }}"></div>
                                </div>
                                <div class="flex-1 relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">#</span>
                                    <input type="text" name="color" id="color-text" value="{{ old('color', '#2DD4BF') }}" 
                                           class="w-full pl-8 pr-4 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-mono font-bold text-slate-700 uppercase"
                                           oninput="if(this.value.startsWith('#')) { updateColor(this.value); } else { updateColor('#' + this.value); }">
                                </div>
                            </div>
                        </div>
                        @error('color') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sort Order --}}
                    <div class="space-y-1.5">
                        <label for="sort_order" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Display Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" 
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700" required>
                    </div>
                </div>

                {{-- Status Toggle --}}
                <div class="py-6 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-700">Display this service?</h4>
                        <p class="text-[11px] text-slate-400 font-medium">If disabled, this service category will not appear on Kiosk.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none ring-0 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                    </label>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" id="btn-save" class="flex-1 h-12 bg-teal-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition duration-200">
                        Save Service
                    </button>
                    <a href="{{ route('admin.service-types.index') }}" class="px-8 h-12 flex items-center justify-center border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let codeTimeout = null;
    let isCodeValid = true;

    // Form Submit Handler
    document.getElementById('service-form').addEventListener('submit', function(e) {
        const nameValue = document.getElementById('name').value;
        const codeValue = document.getElementById('code').value;
        
        validateName(nameValue);
        validateCode(codeValue);

        const hasErrorName = !document.getElementById('error-name').classList.contains('hidden');
        const hasErrorCode = !document.getElementById('error-code').classList.contains('hidden');

        if (hasErrorName || hasErrorCode || !isCodeValid) {
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
        const input = document.getElementById('name');
        const error = document.getElementById('error-name');
        
        if (value.trim() === '') {
            input.classList.add('border-red-300', 'ring-4', 'ring-red-50');
            error.classList.remove('hidden');
        } else {
            input.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
            error.classList.add('hidden');
        }
    }

    function validateCode(value) {
        const input = document.getElementById('code');
        const error = document.getElementById('error-code');
        
        if (value.trim() === '') {
            input.classList.add('border-red-300', 'ring-4', 'ring-red-50');
            error.innerText = 'Kode layanan tidak boleh kosong.';
            error.classList.remove('hidden');
            isCodeValid = false;
            return;
        }

        // AJAX Uniqueness Check (Debounced)
        clearTimeout(codeTimeout);
        codeTimeout = setTimeout(() => {
            fetch('{{ route('admin.service-types.check-code') }}?code=' + encodeURIComponent(value))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        input.classList.add('border-red-300', 'ring-4', 'ring-red-50');
                        error.innerText = 'Kode layanan "' + value + '" sudah digunakan.';
                        error.classList.remove('hidden');
                        isCodeValid = false;
                    } else {
                        input.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
                        error.classList.add('hidden');
                        isCodeValid = true;
                    }
                });
        }, 500);
    }
</script>
@endsection
