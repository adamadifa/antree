@extends('layouts.admin')

@section('title', 'Display Settings')
@section('header', 'Queue Display Configuration')

@section('content')
<!-- Import Tabler Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

<div class="max-w-3xl space-y-6">
    {{-- Breadcrumb Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Display Settings</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-400 mt-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-655 transition">
                    <i class="ti ti-home text-sm align-middle"></i>
                </a>
                <span>/</span>
                <span>System Settings</span>
                <span>/</span>
                <span class="text-rose-500 font-semibold">Display Screen</span>
            </div>
        </div>
    </div>

    {{-- Section 1: Branding & General Settings --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Branding & Layout</h3>
                <p class="text-[11px] text-slate-400 font-medium">Configure the visual identity of your public display.</p>
            </div>
            <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                <i class="ti ti-palette text-rose-500 text-base"></i>
            </div>
        </div>

        <form action="{{ route('admin.display-settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-7">
                {{-- Company Name --}}
                <div class="space-y-1">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Company / Institution Name</label>
                        <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-450 focus-within:ring-4 focus-within:ring-rose-50">
                            <i class="ti ti-building text-slate-400 text-base mr-3 shrink-0"></i>
                            <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}" placeholder="Antree Digital Services"
                                   class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60">
                        </div>
                    </div>
                </div>

                {{-- Display Logo Upload --}}
                <div class="space-y-1">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Display Logo</label>
                        <div class="flex items-center border border-slate-200 rounded-2xl p-3 bg-white">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center flex-shrink-0 p-1 shadow-sm mr-3">
                                @if(!empty($settings['logo_url']))
                                    @if(str_starts_with($settings['logo_url'], 'http://') || str_starts_with($settings['logo_url'], 'https://'))
                                        <img id="display-logo-preview" src="{{ $settings['logo_url'] }}" class="w-full h-full object-contain" alt="Logo">
                                    @elseif(str_starts_with($settings['logo_url'], 'storage/'))
                                        <img id="display-logo-preview" src="{{ asset($settings['logo_url']) }}" class="w-full h-full object-contain" alt="Logo">
                                    @else
                                        <img id="display-logo-preview" src="{{ asset('storage/' . $settings['logo_url']) }}" class="w-full h-full object-contain" alt="Logo">
                                    @endif
                                @else
                                    <div id="display-logo-placeholder" class="text-slate-350">
                                        <i class="ti ti-photo text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label for="logo_file" class="cursor-pointer px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg shadow-sm transition inline-block uppercase tracking-wider">
                                    Choose File
                                </label>
                                <input type="file" name="logo_file" id="logo_file" class="hidden" accept="image/*" onchange="previewDisplayLogo(this)">
                                <p class="text-[9px] text-slate-400 font-medium mt-0.5">PNG or SVG. Max 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Display Layout --}}
                <div class="space-y-1">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Display Layout Style</label>
                        <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1.5 bg-white transition focus-within:border-rose-450 focus-within:ring-4 focus-within:ring-rose-50">
                            <i class="ti ti-layout text-slate-400 text-base mr-3 shrink-0"></i>
                            <select name="display_layout" class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 cursor-pointer">
                                <option value="default" {{ ($settings['display_layout'] ?? 'default') === 'default' ? 'selected' : '' }}>Layout 1</option>
                                <option value="list_counter" {{ ($settings['display_layout'] ?? '') === 'list_counter' ? 'selected' : '' }}>Layout 2</option>
                                <option value="imigrasi" {{ ($settings['display_layout'] ?? '') === 'imigrasi' ? 'selected' : '' }}>Layout 3</option>
                                <option value="lounge" {{ ($settings['display_layout'] ?? '') === 'lounge' ? 'selected' : '' }}>Layout 4</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Primary Color --}}
                <div class="space-y-1">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Primary Color (Header)</label>
                        <div class="flex items-center border border-slate-200 rounded-2xl px-3 py-2 bg-white">
                            <input type="color" name="primary_color" id="primary_color" value="{{ $settings['primary_color'] ?? '#0D9488' }}"
                                   onchange="document.getElementById('primary_color_text').value = this.value"
                                   class="w-8 h-8 rounded-lg border border-slate-200 cursor-pointer p-0.5 bg-white mr-3 shrink-0">
                            <input type="text" id="primary_color_text" value="{{ $settings['primary_color'] ?? '#0D9488' }}" readonly
                                   class="w-full bg-transparent text-xs font-mono font-bold text-slate-550 border-0 focus:ring-0 outline-none py-1">
                        </div>
                    </div>
                </div>

                {{-- Accent Color --}}
                <div class="space-y-1">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Accent Color (Footer)</label>
                        <div class="flex items-center border border-slate-200 rounded-2xl px-3 py-2 bg-white">
                            <input type="color" name="accent_color" id="accent_color" value="{{ $settings['accent_color'] ?? '#262626' }}"
                                   onchange="document.getElementById('accent_color_text').value = this.value"
                                   class="w-8 h-8 rounded-lg border border-slate-200 cursor-pointer p-0.5 bg-white mr-3 shrink-0">
                            <input type="text" id="accent_color_text" value="{{ $settings['accent_color'] ?? '#262626' }}" readonly
                                   class="w-full bg-transparent text-xs font-mono font-bold text-slate-550 border-0 focus:ring-0 outline-none py-1">
                        </div>
                    </div>
                </div>

                {{-- Running Text --}}
                <div class="space-y-1 md:col-span-2">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-xs font-bold text-slate-500">Running Text (Marquee)</label>
                        <div class="flex items-start border border-slate-200 rounded-2xl px-4 py-2 bg-white transition focus-within:border-rose-450 focus-within:ring-4 focus-within:ring-rose-50">
                            <i class="ti ti-speakerphone text-slate-400 text-base mr-3 mt-2 shrink-0"></i>
                            <textarea name="running_text" rows="2" placeholder="Welcome to Antree! Please wait for your number to be called..."
                                      class="w-full bg-transparent text-sm font-medium text-slate-705 border-0 focus:ring-0 outline-none py-2 placeholder:text-slate-400/60 resize-none">{{ $settings['running_text'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end">
                <button type="submit" class="px-6 h-11 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/10 transition duration-150 flex items-center gap-1.5 uppercase tracking-wider">
                    <i class="ti ti-device-floppy text-sm"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    {{-- Section 2: Media Content Playlist --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Media Content Playlist</h3>
                <p class="text-[11px] text-slate-400 font-medium">Manage videos & images displayed on the queue screen.</p>
            </div>
            <button onclick="openMediaModal()" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition flex items-center gap-1.5 uppercase tracking-wider">
                <i class="ti ti-plus text-sm"></i>
                <span>Add Media</span>
            </button>
        </div>

        <div class="p-6 divide-y divide-slate-100">
            @forelse($mediaContents as $media)
            <div class="flex items-center gap-4 py-3.5 first:pt-0 last:pb-0 group">
                {{-- Thumbnail --}}
                <div class="w-24 h-14 rounded-xl bg-slate-900 overflow-hidden flex-shrink-0 relative flex items-center justify-center shadow-sm">
                    @if($media->type === 'youtube')
                        <img src="https://img.youtube.com/vi/{{ $media->content }}/mqdefault.jpg" class="w-full h-full object-cover opacity-80" alt="">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-6 h-6 bg-rose-600 rounded-full flex items-center justify-center text-white">
                                <i class="ti ti-player-play text-[10px]"></i>
                            </div>
                        </div>
                    @elseif($media->type === 'image')
                        <img src="{{ $media->content }}" class="w-full h-full object-cover" onerror="this.style.display='none'" alt="">
                        <i class="ti ti-photo text-slate-400 text-xl absolute"></i>
                    @else
                        <i class="ti ti-video text-slate-400 text-xl"></i>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-slate-800 text-xs truncate">{{ $media->title }}</h4>
                    <div class="flex items-center gap-2.5 mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-[9px] font-bold text-slate-500 uppercase tracking-wider border border-slate-200">{{ $media->type }}</span>
                        <span class="text-[9px] text-slate-400 font-semibold">Order: #{{ $media->sort_order }}</span>
                        <span class="inline-flex items-center gap-1 text-[9px] text-emerald-600 font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-450"></span> Active
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition duration-150">
                    <form action="{{ route('admin.display-settings.destroy-media', $media) }}" method="POST" onsubmit="return confirm('Remove this media from playlist?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-red-500 hover:bg-red-50 hover:text-red-655 transition border border-slate-200/50" title="Remove">
                            <i class="ti ti-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="py-12 text-center">
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="ti ti-video-off text-2xl text-slate-300"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-700">No media content yet</h4>
                <p class="text-slate-400 text-[10px] mt-0.5">Add YouTube videos, images, or text slides to your display rotation.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Add Media Modal --}}
<div id="media-modal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4">
    {{-- Dark Backdrop --}}
    <div class="fixed inset-0" style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" onclick="closeMediaModal()"></div>

    {{-- Modal Panel --}}
    <div id="modal-panel" class="relative bg-white w-full max-w-sm rounded-[2rem] shadow-2xl overflow-hidden transform transition-all duration-200 scale-90 opacity-0">
        {{-- Inner Header --}}
        <div class="px-7 py-4.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Add New Media</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Media Playlist</p>
            </div>
            <button onclick="closeMediaModal()" class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-450 hover:text-slate-900 transition-colors">
                <i class="ti ti-x text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.display-settings.store-media') }}" method="POST" class="p-6 space-y-5">
            @csrf
            {{-- Title --}}
            <div class="space-y-1">
                <div class="relative">
                    <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Title</label>
                    <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1">
                        <i class="ti ti-heading text-slate-400 mr-2.5"></i>
                        <input type="text" name="title" required placeholder="Header Promotion" 
                               class="w-full bg-transparent border-0 focus:ring-0 outline-none py-2 text-xs font-semibold">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Type --}}
                <div class="space-y-1">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Type</label>
                        <div class="flex items-center border border-slate-200 rounded-2xl px-3 py-1">
                            <select name="type" required id="media-type" onchange="updatePlaceholder(this.value)"
                                    class="w-full bg-transparent border-0 focus:ring-0 outline-none py-2 text-xs font-semibold cursor-pointer">
                                <option value="youtube">YouTube</option>
                                <option value="image">Image</option>
                                <option value="video">MP4 Video</option>
                                <option value="text">Info Slide</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- Sort --}}
                <div class="space-y-1">
                    <div class="relative">
                        <label class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Order</label>
                        <div class="flex items-center border border-slate-200 rounded-2xl px-3 py-1">
                            <input type="number" name="sort_order" value="1" min="1"
                                   class="w-full bg-transparent border-0 focus:ring-0 outline-none py-2 text-xs font-semibold">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="space-y-1">
                <div class="relative">
                    <label id="content-label" class="absolute -top-2.5 left-4 bg-white px-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">YouTube Video ID</label>
                    <div class="flex items-center border border-slate-200 rounded-2xl px-4 py-1">
                        <i class="ti ti-link text-slate-400 mr-2.5"></i>
                        <input type="text" name="content" id="media-content-input" required placeholder="dQw4w9WgXcQ" 
                               class="w-full bg-transparent border-0 focus:ring-0 outline-none py-2 text-xs font-semibold">
                    </div>
                </div>
                <p id="content-help" class="text-[9px] text-slate-400 font-semibold px-1 mt-1 leading-tight">Paste only the unique ID from URL.</p>
            </div>

            <div class="pt-2 flex flex-col gap-2">
                <button type="submit" class="w-full h-11 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-500/25 transition duration-150 text-xs uppercase tracking-wider">
                    Add Content
                </button>
                <button type="button" onclick="closeMediaModal()" class="w-full text-slate-400 font-bold hover:text-slate-655 transition text-[10px] py-1.5 uppercase tracking-wider">
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
