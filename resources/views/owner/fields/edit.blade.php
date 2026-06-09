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

            <!-- Image URL -->
            <div class="flex flex-col space-y-1">
                <label class="text-xs font-headline font-bold uppercase tracking-widest text-secondary px-2" for="image_url">Image Showcase URL</label>
                <input class="w-full px-5 py-3 rounded-xl bg-surface-variant border-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/40" id="image_url" name="image_url" value="{{ old('image_url', $field->image_url) }}" placeholder="https://images.unsplash.com/..." type="url"/>
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
