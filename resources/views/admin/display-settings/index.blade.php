@extends('layouts.admin')

@section('title', 'Display Settings')
@section('header', 'Queue Display Configuration')

@section('content')
<div class="space-y-6">
    {{-- Section 1: Branding & General Settings --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Branding & Layout</h3>
                <p class="text-xs text-slate-500 mt-1">Configure the visual identity of your public display</p>
            </div>
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <form action="{{ route('admin.display-settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Company Name --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Company / Institution Name</label>
                    <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}" placeholder="Antree Digital Services"
                           class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                </div>

                {{-- Display Logo Upload --}}
                <div class="space-y-1.5 col-span-1">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Display Logo</label>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center flex-shrink-0 p-1 shadow-sm">
                            @if(!empty($settings['logo_url']))
                                @if(str_starts_with($settings['logo_url'], 'http://') || str_starts_with($settings['logo_url'], 'https://'))
                                    <img id="display-logo-preview" src="{{ $settings['logo_url'] }}" class="w-full h-full object-contain" alt="Logo">
                                @elseif(str_starts_with($settings['logo_url'], 'storage/'))
                                    <img id="display-logo-preview" src="{{ asset($settings['logo_url']) }}" class="w-full h-full object-contain" alt="Logo">
                                @else
                                    <img id="display-logo-preview" src="{{ asset('storage/' . $settings['logo_url']) }}" class="w-full h-full object-contain" alt="Logo">
                                @endif
                            @else
                                <div id="display-logo-placeholder" class="text-slate-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label for="logo_file" class="cursor-pointer px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl shadow-sm hover:bg-slate-50 transition duration-150 inline-block">
                                Choose File
                            </label>
                            <input type="file" name="logo_file" id="logo_file" class="hidden" accept="image/*" onchange="previewDisplayLogo(this)">
                            <p class="text-[10px] text-slate-400 font-medium mt-1">Recommended: square PNG or SVG. Max 2MB.</p>
                        </div>
                    </div>
                </div>

                {{-- Display Layout --}}
                <div class="space-y-1.5 col-span-1">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Display Layout Style</label>
                    <select name="display_layout" class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700">
                        <option value="default" {{ ($settings['display_layout'] ?? 'default') === 'default' ? 'selected' : '' }}>Layout Grid (Default)</option>
                        <option value="list_counter" {{ ($settings['display_layout'] ?? '') === 'list_counter' ? 'selected' : '' }}>Layout List Counter (BNI Life Style)</option>
                    </select>
                </div>

                {{-- Running Text --}}
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Running Text (Marquee)</label>
                    <textarea name="running_text" rows="3" placeholder="Welcome to Antree! Please wait for your number to be called..."
                              class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition duration-200 text-sm font-medium text-slate-700 placeholder:text-slate-300 resize-none">{{ $settings['running_text'] ?? '' }}</textarea>
                </div>

                {{-- Primary Color --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Primary Color (Header)</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="primary_color" id="primary_color" value="{{ $settings['primary_color'] ?? '#0D9488' }}"
                               onchange="document.getElementById('primary_color_text').value = this.value"
                               class="w-11 h-11 rounded-xl border border-slate-200 cursor-pointer p-1 bg-white">
                        <input type="text" id="primary_color_text" value="{{ $settings['primary_color'] ?? '#0D9488' }}" readonly
                               class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-500">
                    </div>
                </div>

                {{-- Accent Color --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Accent Color (Footer)</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="accent_color" id="accent_color" value="{{ $settings['accent_color'] ?? '#262626' }}"
                               onchange="document.getElementById('accent_color_text').value = this.value"
                               class="w-11 h-11 rounded-xl border border-slate-200 cursor-pointer p-1 bg-white">
                        <input type="text" id="accent_color_text" value="{{ $settings['accent_color'] ?? '#262626' }}" readonly
                               class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-500">
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-8 h-12 bg-teal-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    {{-- Section 2: Media Content Playlist --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Media Content Playlist</h3>
                <p class="text-xs text-slate-500 mt-1">Manage videos & images displayed on the queue screen</p>
            </div>
            <button onclick="openMediaModal()" class="px-5 py-2.5 bg-teal-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition flex items-center gap-2 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Media</span>
            </button>
        </div>

        <div class="p-6">
            @forelse($mediaContents as $media)
            <div class="flex items-center gap-5 p-4 rounded-2xl hover:bg-slate-50/70 transition duration-200 group {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                {{-- Thumbnail --}}
                <div class="w-28 h-16 rounded-xl bg-slate-900 overflow-hidden flex-shrink-0 relative flex items-center justify-center">
                    @if($media->type === 'youtube')
                        <img src="https://img.youtube.com/vi/{{ $media->content }}/mqdefault.jpg" class="w-full h-full object-cover opacity-80" alt="">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white">
                                <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    @elseif($media->type === 'image')
                        <img src="{{ $media->content }}" class="w-full h-full object-cover" onerror="this.style.display='none'" alt="">
                        <svg class="w-6 h-6 text-slate-500 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @else
                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-slate-800 text-sm truncate">{{ $media->title }}</h4>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $media->type }}</span>
                        <span class="text-[10px] text-slate-400 font-medium">Order: #{{ $media->sort_order }}</span>
                        <span class="flex items-center gap-1 text-[10px] text-emerald-500 font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition duration-200">
                    <form action="{{ route('admin.display-settings.destroy-media', $media) }}" method="POST" onsubmit="return confirm('Remove this media from playlist?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition duration-200 border border-red-100" title="Remove">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="py-16 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-slate-700 font-bold">No media content yet</h3>
                <p class="text-slate-400 text-xs mt-1">Add YouTube videos, images, or text slides to your display rotation.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Add Media Modal --}}
<div id="media-modal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4">
    {{-- Dark Backdrop (Guaranteed Visibility) --}}
    <div class="fixed inset-0" style="background-color: rgba(0,0,0,0.7); backdrop-filter: blur(4px);" onclick="closeMediaModal()"></div>

    {{-- Modal Panel (Narrow & Clean) --}}
    <div id="modal-panel" class="relative bg-white w-full max-w-sm rounded-[2rem] shadow-2xl overflow-hidden transform transition-all duration-200 scale-90 opacity-0">
        {{-- Inner Header --}}
        <div class="px-7 py-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800">Add New Media</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Media Playlist</p>
            </div>
            <button onclick="closeMediaModal()" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.display-settings.store-media') }}" method="POST" class="p-7 space-y-4">
            @csrf
            {{-- Title --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Title</label>
                <input type="text" name="title" required placeholder="Header Promotion" 
                       class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Type --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Type</label>
                    <select name="type" required id="media-type" onchange="updatePlaceholder(this.value)"
                            class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-500 transition text-sm cursor-pointer">
                        <option value="youtube">YouTube</option>
                        <option value="image">Image</option>
                        <option value="video">MP4 Video</option>
                        <option value="text">Info Slide</option>
                    </select>
                </div>
                {{-- Sort --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Order</label>
                    <input type="number" name="sort_order" value="1" min="1"
                           class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 text-sm">
                </div>
            </div>

            {{-- Content --}}
            <div class="space-y-1.5">
                <label id="content-label" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">YouTube Video ID</label>
                <input type="text" name="content" id="media-content-input" required placeholder="dQw4w9WgXcQ" 
                       class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-teal-50 text-sm">
                <p id="content-help" class="text-[9px] text-slate-400 font-medium px-1 mt-1 leading-tight">Paste only the unique ID from URL.</p>
            </div>

            <div class="pt-4 flex flex-col gap-2">
                <button type="submit" class="w-full h-12 bg-teal-500 text-white font-bold rounded-2xl shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition duration-200 text-sm">
                    Add Content
                </button>
                <button type="button" onclick="closeMediaModal()" class="w-full text-slate-400 font-bold hover:text-slate-600 transition text-xs py-2">
                    Discard Changes
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openMediaModal() {
        const modal = document.getElementById('media-modal');
        const panel = document.getElementById('modal-panel');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            panel.classList.remove('scale-90', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeMediaModal() {
        const modal = document.getElementById('media-modal');
        const panel = document.getElementById('modal-panel');
        panel.classList.remove('scale-100', 'opacity-100');
        panel.classList.add('scale-90', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 200);
    }

    function updatePlaceholder(type) {
        const input = document.getElementById('media-content-input');
        const label = document.getElementById('content-label');
        const help = document.getElementById('content-help');

        const config = {
            youtube: { label: 'YouTube Video ID', placeholder: 'dQw4w9WgXcQ', help: 'The string after v=.' },
            image: { label: 'Image URL', placeholder: 'https://...', help: 'Direct link to an image file.' },
            video: { label: 'Video URL', placeholder: 'https://...', help: 'Direct link to an MP4/WebM file.' },
            text: { label: 'Display Text', placeholder: 'Enter text...', help: 'This text will be shown on a slide.' }
        };

        const c = config[type] || config.youtube;
        label.textContent = c.label;
        input.placeholder = c.placeholder;
        help.textContent = c.help;
    }

    function previewDisplayLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                let previewImg = document.getElementById('display-logo-preview');
                const placeholder = document.getElementById('display-logo-placeholder');
                
                if (!previewImg) {
                    previewImg = document.createElement('img');
                    previewImg.id = 'display-logo-preview';
                    previewImg.className = 'w-full h-full object-contain';
                    if (placeholder) {
                        placeholder.parentNode.replaceChild(previewImg, placeholder);
                    }
                }
                
                previewImg.src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMediaModal();
    });
</script>
@endpush
@endsection
