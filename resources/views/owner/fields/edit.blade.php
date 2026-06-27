@extends('layouts.app')

@section('title', 'Edit Field: ' . $field->name)

@section('content')
<div class="max-w-screen-md mx-auto px-6 py-12">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-2 text-primary hover:underline font-bold text-sm mb-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Command Center
        </a>
        <h1 class="font-headline font-black text-4xl text-on-surface">Edit Court Registry</h1>
        <p class="text-on-surface-variant font-medium mt-1">Configure field parameters for "{{ $field->name }}".</p>
    </div>

    <!-- Card Form -->
    <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-surface-variant/20">
        <form action="{{ route('owner.fields.update', $field->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="flex flex-col space-y-1">
                <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="name">Court Name</label>
                <input class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/40" id="name" name="name" value="{{ old('name', $field->name) }}" placeholder="Apex Padel Arena B" type="text" required/>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sport Type -->
                <div class="flex flex-col space-y-1">
                    <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="sport_type">Sport Type</label>
                    <select class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all" id="sport_type" name="sport_type" required>
                        <option value="padel" {{ old('sport_type', $field->sport_type) === 'padel' ? 'selected' : '' }}>Padel</option>
                        <option value="tennis" {{ old('sport_type', $field->sport_type) === 'tennis' ? 'selected' : '' }}>Tennis</option>
                        <option value="badminton" {{ old('sport_type', $field->sport_type) === 'badminton' ? 'selected' : '' }}>Badminton</option>
                    </select>
                </div>

                <!-- Price Per Hour -->
                <div class="flex flex-col space-y-1">
                    <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="price_per_hour">Price Per Hour ($)</label>
                    <input class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/40" id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour', $field->price_per_hour) }}" placeholder="45.00" type="number" step="0.01" min="0" required/>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Capacity -->
                <div class="flex flex-col space-y-1">
                    <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="capacity">Player Capacity</label>
                    <input class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/40" id="capacity" name="capacity" value="{{ old('capacity', $field->capacity) }}" placeholder="4" type="number" min="1" required/>
                </div>

                <!-- Indoor / Outdoor -->
                <div class="flex flex-col space-y-1">
                    <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="is_indoor">Setup Environment</label>
                    <select class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all" id="is_indoor" name="is_indoor" required>
                        <option value="1" {{ old('is_indoor', $field->is_indoor ? '1' : '0') === '1' ? 'selected' : '' }}>Indoor</option>
                        <option value="0" {{ old('is_indoor', $field->is_indoor ? '1' : '0') === '0' ? 'selected' : '' }}>Outdoor</option>
                    </select>
                </div>
            </div>

            <!-- Location -->
            <div class="flex flex-col space-y-1">
                <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="location">Location Address</label>
                <input class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/40" id="location" name="location" value="{{ old('location', $field->location) }}" placeholder="Chamartín, Madrid" type="text" required/>
            </div>

            <!-- Features -->
            <div class="flex flex-col space-y-1">
                <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="features">Features (Comma separated)</label>
                <input class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/40" id="features" name="features" value="{{ old('features', implode(', ', $field->features ?: [])) }}" placeholder="Pro-Turf, Showers, Locker Rooms, Night Lights" type="text"/>
            </div>

            <!-- Existing Images list -->
            @if(!empty($field->image_url))
                <div class="flex flex-col space-y-2">
                    <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2">Existing Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="existing-images-container">
                        @foreach($field->image_url as $image)
                            <div class="relative aspect-square rounded-xl overflow-hidden group shadow-sm bg-surface-variant border border-surface-variant/50 transition-all" data-url="{{ $image }}">
                                <img class="w-full h-full object-cover" src="{{ $image }}" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" class="btn-remove-existing p-2 bg-red-600 hover:bg-red-700 text-white rounded-full transition-transform active:scale-95 shadow-md" data-url="{{ $image }}">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Track Deleted Images -->
            <div id="deleted-images-inputs"></div>

            <!-- Multiple Images Upload (Drag & Drop) -->
            <div class="flex flex-col space-y-2">
                <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2">Upload New Images</label>
                
                <div id="dropzone" class="border-2 border-dashed border-[#0052d0]/30 hover:border-[#0052d0] rounded-2xl p-8 flex flex-col items-center justify-center bg-surface-variant/30 hover:bg-surface-variant/50 transition-all cursor-pointer group">
                    <span class="material-symbols-outlined text-4xl text-[#0052d0]/60 group-hover:scale-110 transition-transform duration-300">cloud_upload</span>
                    <p class="font-body text-sm font-bold text-on-surface mt-3">Drag & drop your images here</p>
                    <p class="font-body text-xs text-on-surface-variant mt-1">or click to browse from files</p>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden" />
                </div>

                <!-- Preview Container -->
                <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4">
                    <!-- Dynamic preview cards will be injected here -->
                </div>
            </div>

            <!-- Status -->
            <div class="flex flex-col space-y-1">
                <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="status">Current Status</label>
                <select class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all" id="status" name="status" required>
                    <option value="active" {{ old('status', $field->status) === 'active' ? 'selected' : '' }}>Active / Open</option>
                    <option value="maintenance" {{ old('status', $field->status) === 'maintenance' ? 'selected' : '' }}>Maintenance Needed</option>
                    <option value="cleaning" {{ old('status', $field->status) === 'cleaning' ? 'selected' : '' }}>Cleaning Pending</option>
                </select>
            </div>

            <!-- Description -->
            <div class="flex flex-col space-y-1">
                <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="description">Arena Description</label>
                <textarea class="w-full bg-surface-variant/40 border-none rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all font-body text-sm" id="description" name="description" rows="4" placeholder="Describe the environment, material traction, spectator capacity...">{{ old('description', $field->description) }}</textarea>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-dim text-white font-headline font-bold py-4 rounded-xl shadow-lg active:scale-95 transition-all">
                Update Court Registry
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('preview-container');
    const existingContainer = document.getElementById('existing-images-container');
    const deletedInputsContainer = document.getElementById('deleted-images-inputs');
    const form = fileInput.closest('form');
    
    let selectedFiles = [];

    // Force multipart/form-data
    form.setAttribute('enctype', 'multipart/form-data');

    // Handle existing images deletion
    if (existingContainer) {
        existingContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('.btn-remove-existing');
            if (!btn) return;
            
            const imageUrl = btn.getAttribute('data-url');
            
            // Add hidden input to notify backend of deletion
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleted_images[]';
            input.value = imageUrl;
            deletedInputsContainer.appendChild(input);
            
            // Remove card from DOM
            const card = btn.closest('[data-url]');
            if (card) card.remove();
        });
    }

    // Trigger file dialog
    dropzone.addEventListener('click', () => fileInput.click());

    // Drag-drop events
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.add('border-[#0052d0]', 'bg-surface-variant/70');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-[#0052d0]', 'bg-surface-variant/70');
        }, false);
    });

    dropzone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file.type.startsWith('image/')) continue;
            
            selectedFiles.push(file);
            
            const reader = new FileReader();
            reader.onload = (event) => {
                const card = document.createElement('div');
                card.className = 'relative aspect-square rounded-xl overflow-hidden group shadow-sm bg-surface-variant border border-surface-variant/50 transition-all';
                
                const fileIndex = selectedFiles.length - 1;
                card.setAttribute('data-index', fileIndex);

                card.innerHTML = `
                    <img class="w-full h-full object-cover" src="${event.target.result}" />
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button type="button" class="btn-remove p-2 bg-red-600 hover:bg-red-700 text-white rounded-full transition-transform active:scale-95 shadow-md" data-index="${fileIndex}">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(card);
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove handlers
    previewContainer.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove');
        if (!btn) return;
        
        const index = parseInt(btn.getAttribute('data-index'));
        selectedFiles.splice(index, 1);
        renderAllPreviews();
    });

    function renderAllPreviews() {
        previewContainer.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const card = document.createElement('div');
                card.className = 'relative aspect-square rounded-xl overflow-hidden group shadow-sm bg-surface-variant border border-surface-variant/50 transition-all';
                card.setAttribute('data-index', index);
                card.innerHTML = `
                    <img class="w-full h-full object-cover" src="${event.target.result}" />
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button type="button" class="btn-remove p-2 bg-red-600 hover:bg-red-700 text-white rounded-full transition-transform active:scale-95 shadow-md" data-index="${index}">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
    }

    // Intercept submit
    form.addEventListener('submit', (e) => {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        fileInput.files = dt.files;
    });
});
</script>
@endsection
