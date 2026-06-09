@extends('layouts.app')

@section('title', 'Find Your Field')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 py-8">
    <!-- Hero Search & Filters -->
    <section class="mb-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-2xl w-full">
                <h1 class="headline-font text-5xl md:text-7xl font-extrabold tracking-tighter text-on-surface leading-tight">
                    UNLEASH THE <span class="text-primary italic">KINETIC</span> PULSE.
                </h1>
                <p class="mt-4 text-on-surface-variant font-medium text-lg md:text-xl max-w-lg">
                    Premium courts. Elite performance. Book your next match in seconds.
                </p>
                
                <!-- Search Input & Visual Context -->
                <form action="{{ route('fields.index') }}" method="GET" class="mt-6 w-full max-w-lg">
                    @if($sport)
                        <input type="hidden" name="sport" value="{{ $sport }}"/>
                    @endif
                    <div class="bg-white border border-[#E2E8F0] rounded-xl px-4 py-3 flex items-center gap-3 w-full shadow-sm">
                        <span class="material-symbols-outlined text-[#004EEB]">search</span>
                        <input type="text" name="q" value="{{ $query }}" class="bg-transparent border-none focus:ring-0 text-sm font-medium placeholder-[#64748B] w-full text-on-surface" placeholder="Search court name..."/>
                        @if($query || $sport)
                            <a href="{{ route('fields.index') }}" class="text-xs text-red-600 hover:underline font-bold shrink-0">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Sport Tabs (Padel, Tennis, Badminton only) -->
        <div class="mt-12 flex gap-4 overflow-x-auto pb-4 no-scrollbar">
            <a href="{{ route('fields.index', ['sport' => '', 'q' => $query]) }}" 
               class="px-8 py-4 rounded-full font-bold headline-font flex items-center gap-2 kinetic-skew transition-all shadow-md {{ !$sport ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined">sports_handball</span>
                All Sports
            </a>
            
            <a href="{{ route('fields.index', ['sport' => 'padel', 'q' => $query]) }}" 
               class="px-8 py-4 rounded-full font-bold headline-font flex items-center gap-2 kinetic-skew transition-all shadow-md {{ $sport === 'padel' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined">sports_tennis</span>
                Padel
            </a>
            
            <a href="{{ route('fields.index', ['sport' => 'tennis', 'q' => $query]) }}" 
               class="px-8 py-4 rounded-full font-bold headline-font flex items-center gap-2 kinetic-skew transition-all shadow-md {{ $sport === 'tennis' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined">sports_tennis</span>
                Tennis
            </a>
            
            <a href="{{ route('fields.index', ['sport' => 'badminton', 'q' => $query]) }}" 
               class="px-8 py-4 rounded-full font-bold headline-font flex items-center gap-2 kinetic-skew transition-all shadow-md {{ $sport === 'badminton' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                <span class="material-symbols-outlined">sports_handball</span>
                Badminton
            </a>
        </div>
    </section>

    <!-- Main Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
        @forelse($fields as $field)
            <!-- Field Card -->
            <div class="group bg-surface-container-lowest rounded-lg overflow-hidden shadow-[0_20px_40px_rgba(13,54,28,0.08)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="relative h-64 overflow-hidden bg-surface-variant">
                        @if($field->image_url)
                            <img alt="{{ $field->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $field->image_url }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-[#bff5c8] text-primary">
                                <span class="material-symbols-outlined text-6xl">sports_tennis</span>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-surface-container-lowest/90 backdrop-blur px-3 py-1 rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-tertiary text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="font-bold text-xs text-on-surface">{{ $field->average_rating > 0 ? number_format($field->average_rating, 1) : 'New' }}</span>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-primary text-white font-black text-xs uppercase px-3 py-1 rounded-full">{{ $field->sport_type }}</span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <h3 class="headline-font text-2xl font-bold text-on-surface leading-snug">{{ $field->name }}</h3>
                            <span class="text-primary font-black text-xl whitespace-nowrap">${{ number_format($field->price_per_hour, 0) }}<span class="text-xs font-medium text-on-surface-variant">/hr</span></span>
                        </div>
                        <div class="flex items-center gap-2 text-on-surface-variant text-sm mb-4">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <span class="truncate">{{ $field->location }}</span>
                        </div>
                        <div class="flex gap-2 flex-wrap mb-4">
                            <span class="bg-surface-container text-on-secondary-container px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $field->is_indoor ? 'Indoor' : 'Outdoor' }}</span>
                            <span class="bg-surface-container text-on-secondary-container px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Cap: {{ $field->capacity }} Players</span>
                            @if($field->features)
                                @foreach(array_slice($field->features, 0, 2) as $feat)
                                    <span class="bg-surface-container text-on-secondary-container px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $feat }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0">
                    <a href="{{ route('fields.show', $field->id) }}" class="block text-center w-full bg-primary hover:bg-primary-dim text-on-primary py-4 rounded-xl font-bold headline-font transition-all duration-300 active:scale-95 shadow-md shadow-primary/15">
                        BOOK NOW
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <span class="material-symbols-outlined text-6xl text-outline mb-4">search_off</span>
                <h3 class="font-headline text-2xl font-bold text-on-surface">No fields available</h3>
                <p class="text-on-surface-variant">We couldn't find any courts matching your criteria. Try adjusting your filters.</p>
            </div>
        @endforelse


    </section>
</div>
@endsection
