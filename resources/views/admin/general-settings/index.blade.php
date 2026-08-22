@extends('layouts.admin')

@section('title', 'General Settings')
@section('header', 'General Configuration')

@section('content')
<!-- Import Tabler Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

<div class="max-w-3xl space-y-6">
    {{-- Breadcrumb Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">General Settings</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-655 transition">
                    <i class="ti ti-home text-sm align-middle"></i>
                </a>
                <span>/</span>
                <span>System Settings</span>
                <span>/</span>
                <span class="text-rose-500 font-semibold">General Settings</span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/85 shadow-sm overflow-hidden flex flex-col">
        {{-- Card Header --}}
        <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Application & Company Identity</h3>
                <p class="text-[11px] text-slate-400 font-medium">Manage logo, system naming, contacts, and default app settings.</p>
            </div>
            <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                <i class="ti ti-settings text-rose-500 text-base"></i>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.general-settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                    <div class="flex items-center space-x-2 text-rose-600 mb-2">
                        <i class="ti ti-alert-triangle text-base mr-1"></i>
                        <span class="font-bold text-sm">Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc pl-5 text-xs text-rose-500 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Logo Upload (Left Col) --}}
                <div class="md:col-span-1 space-y-3">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Application Logo</label>
                    
                    <div class="relative group border-2 border-dashed border-slate-200 hover:border-rose-400 rounded-3xl p-5 transition duration-200 bg-slate-50/50 flex flex-col items-center justify-center text-center h-64 overflow-hidden">
                        {{-- Logo Preview --}}
                        <div id="logo-preview-container" class="w-28 h-28 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center overflow-hidden mb-3">
                            @if($institution->logo_path)
                                <img id="logo-preview" src="{{ asset($institution->logo_path) }}" class="w-full h-full object-contain p-2" alt="Logo">
                            @else
                                <div id="logo-placeholder" class="text-slate-300 flex flex-col items-center">
                                    <i class="ti ti-photo-off text-3xl mb-1"></i>
                                    <span class="text-[10px] font-semibold text-slate-400">No Logo</span>
                                </div>
                            @endif
                            <img id="logo-preview-new" class="w-full h-full object-contain p-2 hidden" alt="New Logo Preview">
                        </div>

                        {{-- Action Overlay --}}
                        <label for="logo" class="cursor-pointer px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 text-[10px] font-bold rounded-xl border border-slate-200 shadow-sm transition uppercase tracking-wider">
                            Upload Logo
                        </label>
                        <input type="file" name="logo" id="logo" class="hidden" accept="image/*" onchange="previewImage(this)">
                        
                        <p class="text-[9px] text-slate-400 font-medium mt-3 leading-relaxed">
                            Square size recommended. Max 2MB.
                        </p>
                    </div>
                </div>

                {{-- Branding and Contact Settings (Right Col) --}}
                <div class="md:col-span-2 flex flex-col gap-6">
                    {{-- App Name --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Application Name</label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <i class="ti ti-device-laptop text-slate-400 text-base mr-3 shrink-0"></i>
                                <input type="text" name="app_name" value="{{ old('app_name', $institution->app_name) }}" required placeholder="Antree"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                    </div>

                    {{-- Company Name --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Company Name</label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <i class="ti ti-building text-slate-400 text-base mr-3 shrink-0"></i>
                                <input type="text" name="name" value="{{ old('name', $institution->name) }}" required placeholder="Pusat Layanan Publik"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Phone Number</label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <i class="ti ti-phone text-slate-400 text-base mr-3 shrink-0"></i>
                                <input type="text" name="phone" value="{{ old('phone', $institution->phone) }}" placeholder="021-12345678"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Contact Email</label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <i class="ti ti-mail text-slate-400 text-base mr-3 shrink-0"></i>
                                <input type="email" name="email" value="{{ old('email', $institution->email) }}" placeholder="support@antree.local"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Office Address</label>
                            <div class="flex items-start border border-slate-200 rounded-2xl px-4 py-2 bg-white transition focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <i class="ti ti-map-pin text-slate-400 text-base mr-3 mt-2 shrink-0"></i>
                                <textarea name="address" rows="2" placeholder="Jl. Merdeka No. 123, Jayakarta..."
                                          class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60 resize-none">{{ old('address', $institution->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Operating Hours --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Operating Hours</label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <i class="ti ti-clock text-slate-400 text-base mr-3 shrink-0"></i>
                                <input type="text" name="operating_hours" value="{{ old('operating_hours', $institution->operating_hours) }}" placeholder="Senin - Jumat: 08:00 - 16:00"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                    </div>

                    {{-- Footer text --}}
                    <div class="space-y-1">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Footer Copyright Text</label>
                            <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-455 focus-within:ring-4 focus-within:ring-rose-50">
                                <i class="ti ti-copyright text-slate-400 text-base mr-3 shrink-0"></i>
                                <input type="text" name="footer_text" value="{{ old('footer_text', $institution->footer_text) }}" placeholder="Antree - Queue Management"
                                       class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-6 h-11 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/10 transition duration-150 flex items-center gap-1.5 uppercase tracking-wider">
                    <i class="ti ti-device-floppy text-sm"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewImg = document.getElementById('logo-preview-new');
                const placeholder = document.getElementById('logo-placeholder');
                const oldPreview = document.getElementById('logo-preview');
                
                if (oldPreview) {
                    oldPreview.src = e.target.result;
                } else if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
