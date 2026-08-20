@extends('layouts.admin')

@section('title', 'General Settings')
@section('header', 'General Configuration')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Application & Company Identity</h3>
                <p class="text-xs text-slate-500 mt-1">Manage logo, system naming, contacts, and default app settings</p>
            </div>
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.general-settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                    <div class="flex items-center space-x-2 text-rose-600 mb-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
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
                    <label class="text-xs font-semibold text-slate-600 ml-1">Application Logo</label>
                    
                    <div class="relative group border-2 border-dashed border-slate-200 hover:border-teal-400 rounded-3xl p-6 transition duration-200 bg-slate-50/50 flex flex-col items-center justify-center text-center h-64 overflow-hidden">
                        {{-- Logo Preview --}}
                        <div id="logo-preview-container" class="w-32 h-32 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center overflow-hidden mb-3">
                            @if($institution->logo_path)
                                <img id="logo-preview" src="{{ asset($institution->logo_path) }}" class="w-full h-full object-contain p-2" alt="Logo">
                            @else
                                <div id="logo-placeholder" class="text-slate-300 flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-slate-400">No Logo</span>
                                </div>
                            @endif
                            <img id="logo-preview-new" class="w-full h-full object-contain p-2 hidden" alt="New Logo Preview">
                        </div>

                        {{-- Action Overlay --}}
                        <label for="logo" class="cursor-pointer px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-sm transition duration-200">
                            Upload Logo
                        </label>
                        <input type="file" name="logo" id="logo" class="hidden" accept="image/*" onchange="previewImage(this)">
                        
                        <p class="text-[9px] text-slate-400 font-medium mt-3 leading-relaxed">
                            Recommended size: square image (e.g. 512x512px). PNG, JPG, or SVG. Max 2MB.
                        </p>
                    </div>
                </div>

                {{-- Branding and Contact Settings (Right Col) --}}
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- App Name --}}
                    <div class="space-y-1.5 sm:col-span-1">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Application Name</label>
                        <input type="text" name="app_name" value="{{ old('app_name', $institution->app_name) }}" required placeholder="Antree"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-semibold text-slate-700">
                        <span class="text-[10px] text-slate-400 font-medium block ml-1">Displayed as title in pages and browser tabs.</span>
                    </div>

                    {{-- Company Name --}}
                    <div class="space-y-1.5 sm:col-span-1">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Company / Institution Name</label>
                        <input type="text" name="name" value="{{ old('name', $institution->name) }}" required placeholder="Pusat Layanan Publik"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-semibold text-slate-700">
                        <span class="text-[10px] text-slate-400 font-medium block ml-1">Legal name of the entity running this application.</span>
                    </div>

                    {{-- Phone --}}
                    <div class="space-y-1.5 sm:col-span-1">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $institution->phone) }}" placeholder="021-12345678"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-semibold text-slate-700">
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5 sm:col-span-1">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Contact Email</label>
                        <input type="email" name="email" value="{{ old('email', $institution->email) }}" placeholder="support@antree.local"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-semibold text-slate-700">
                    </div>

                    {{-- Address --}}
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Office Address</label>
                        <textarea name="address" rows="3" placeholder="Jl. Merdeka No. 123, Jayakarta..."
                                  class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-semibold text-slate-700 placeholder:text-slate-300 resize-none">{{ old('address', $institution->address) }}</textarea>
                    </div>

                    {{-- Operating Hours --}}
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Operating Hours</label>
                        <input type="text" name="operating_hours" value="{{ old('operating_hours', $institution->operating_hours) }}" placeholder="Senin - Jumat: 08:00 - 16:00, Sabtu: 08:00 - 12:00"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-semibold text-slate-700">
                    </div>

                    {{-- Footer text --}}
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Footer Copyright Text</label>
                        <input type="text" name="footer_text" value="{{ old('footer_text', $institution->footer_text) }}" placeholder="Antree - Professional Queue Management"
                               class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-semibold text-slate-700">
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-8 h-12 bg-teal-500 text-white text-sm font-bold rounded-2xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 hover:scale-[1.02] transition duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save General Settings
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
