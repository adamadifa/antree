@extends('layouts.admin')

@section('title', 'Edit Counter')
@section('header', 'Update Counter Configuration')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden min-h-[500px] flex flex-col">
        {{-- Header Section --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Updating Counter: {{ $counter->name }}</h3>
                <p class="text-xs text-slate-500 mt-1">Modify settings and operator assignments</p>
            </div>
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 font-bold text-lg shadow-sm border border-teal-100">
                {{ $counter->number }}
            </div>
        </div>

        <div class="p-8 flex-1">
            <form id="service-form" action="{{ route('admin.counters.update', $counter) }}" method="POST" class="space-y-6" novalidate>
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Counter Name --}}
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="name" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Counter Display Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $counter->name) }}" placeholder="e.g. Loket 1, Counter A" 
                               oninput="validateName(this.value)"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 @error('name') border-red-300 ring-4 ring-red-50 @enderror">
                        <p id="error-name" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Nama loket tidak boleh kosong.</p>
                        @error('name') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>

                    {{-- Counter Number --}}
                    <div class="space-y-1.5">
                        <label for="number" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Counter Number</label>
                        <input type="number" name="number" id="number" value="{{ old('number', $counter->number) }}" placeholder="e.g. 1, 2, 3" 
                               oninput="validateNumber(this.value)"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 @error('number') border-red-300 ring-4 ring-red-50 @enderror">
                        <p id="error-number" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Nomor loket sudah terdaftar.</p>
                        @error('number') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>

                    {{-- Service Type --}}
                    <div class="space-y-1.5">
                        <label for="service_type_id" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Service Category</label>
                        <select name="service_type_id" id="service_type_id" 
                                class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                            <option value="">Select Category</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}" {{ old('service_type_id', $counter->service_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <p id="error-service_type_id" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Pilih kategori layanan.</p>
                        @error('service_type_id') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>

                    {{-- Operator --}}
                    <div class="space-y-1.5">
                        <label for="user_id" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Assigned Operator</label>
                        <select name="user_id" id="user_id" 
                                class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                            <option value="">Select Operator</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}" {{ old('user_id', $counter->user_id) == $operator->id ? 'selected' : '' }}>{{ $operator->name }}</option>
                            @endforeach
                        </select>
                        <p id="error-user_id" class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize hidden">Pilih operator loket.</p>
                        @error('user_id') <p class="text-[10px] font-bold text-red-500 mt-1 pl-1 capitalize">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1.5">
                        <label for="status" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Counter Status</label>
                        <select name="status" id="status" 
                                class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                            <option value="active" {{ old('status', $counter->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('status', $counter->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="inactive" {{ old('status', $counter->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="pt-8 flex items-center gap-3">
                    <button type="submit" id="btn-save" class="flex-1 h-12 bg-teal-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition duration-200">
                        Update Configuration
                    </button>
                    <a href="{{ route('admin.counters.index') }}" class="px-8 h-12 flex items-center justify-center border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let numberTimeout = null;
    let isNumberValid = true;

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

        const hasErrorName = !document.getElementById('error-name').classList.contains('hidden');
        const hasErrorNumber = !document.getElementById('error-number').classList.contains('hidden');
        const hasErrorServiceType = !document.getElementById('error-service_type_id').classList.contains('hidden');
        const hasErrorUser = !document.getElementById('error-user_id').classList.contains('hidden');

        if (hasErrorName || hasErrorNumber || hasErrorServiceType || hasErrorUser || !isNumberValid) {
            e.preventDefault();
            const firstError = document.querySelector('.border-red-300');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

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

    function validateSelect(id, value) {
        const input = document.getElementById(id);
        const error = document.getElementById('error-' + id);
        if (value === '') {
            input.classList.add('border-red-300', 'ring-4', 'ring-red-50');
            error.classList.remove('hidden');
        } else {
            input.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
            error.classList.add('hidden');
        }
    }

    function validateNumber(value) {
        const input = document.getElementById('number');
        const error = document.getElementById('error-number');
        
        if (value.trim() === '') {
            input.classList.add('border-red-300', 'ring-4', 'ring-red-50');
            error.innerText = 'Nomor loket tidak boleh kosong.';
            error.classList.remove('hidden');
            isNumberValid = false;
            return;
        }

        clearTimeout(numberTimeout);
        numberTimeout = setTimeout(() => {
            fetch('{{ route('admin.counters.check-number') }}?number=' + encodeURIComponent(value) + '&exclude_id={{ $counter->id }}')
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        input.classList.add('border-red-300', 'ring-4', 'ring-red-50');
                        error.innerText = 'Nomor loket "' + value + '" sudah terdaftar.';
                        error.classList.remove('hidden');
                        isNumberValid = false;
                    } else {
                        input.classList.remove('border-red-300', 'ring-4', 'ring-red-50');
                        error.classList.add('hidden');
                        isNumberValid = true;
                    }
                });
        }, 500);
    }
</script>
@endsection
