@extends('layouts.app')

@section('title', $field->name)

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 py-8">
    <!-- Horizontal Scrollable Gallery (Carousel-like) -->
    <section class="mb-12 relative">
        <div class="flex gap-6 overflow-x-auto py-2 px-1 snap-x snap-mandatory scroll-smooth no-scrollbar" id="gallery-scroller" style="scrollbar-width: none; -ms-overflow-style: none;">
            @if(!empty($field->image_url) && count($field->image_url) > 0)
                @foreach($field->image_url as $image)
                    <div class="flex-shrink-0 w-[85%] sm:w-[60%] md:w-[45%] lg:w-[35%] aspect-[16/10] rounded-2xl overflow-hidden shadow-lg border border-surface-variant/20 snap-start relative group">
                        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $field->name }}" src="{{ $image }}"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                @endforeach
            @else
                <div class="flex-shrink-0 w-full h-[320px] rounded-2xl bg-[#bff5c8] text-primary flex flex-col items-center justify-center gap-3 shadow-md">
                    <span class="material-symbols-outlined text-6xl">sports_tennis</span>
                    <p class="font-headline font-bold">No images uploaded for this court.</p>
                </div>
            @endif
        </div>

        <!-- Navigation Buttons (Desktop only) -->
        @if(!empty($field->image_url) && count($field->image_url) > 1)
            <div class="hidden md:flex justify-between items-center absolute inset-y-0 left-0 right-0 pointer-events-none px-4">
                <button onclick="scrollGallery(-1)" class="w-12 h-12 rounded-full bg-white/90 hover:bg-white text-on-surface shadow-lg pointer-events-auto flex items-center justify-center active:scale-95 transition-all">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button onclick="scrollGallery(1)" class="w-12 h-12 rounded-full bg-white/90 hover:bg-white text-on-surface shadow-lg pointer-events-auto flex items-center justify-center active:scale-95 transition-all">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        @endif
    </section>

    <!-- Content Bento Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Information Column -->
        <div class="lg:col-span-7 space-y-8">
            <header>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold font-label uppercase tracking-widest">{{ $field->is_indoor ? 'Premium Indoor' : 'Elite Outdoor' }}</span>
                    <div class="flex items-center text-primary">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="font-bold text-sm ml-1">{{ $field->average_rating > 0 ? number_format($field->average_rating, 1) : 'New' }} ({{ $field->reviews->count() }} reviews)</span>
                    </div>
                </div>
                <h1 class="font-headline font-extrabold text-4xl md:text-6xl text-on-surface tracking-tighter leading-none mb-4">{{ $field->name }}</h1>
                <div class="flex items-center gap-2 text-on-surface-variant font-medium">
                    <span class="material-symbols-outlined">location_on</span>
                    <span>{{ $field->location }}</span>
                </div>
            </header>

            <!-- Specs row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-surface-container-low p-4 rounded-lg flex flex-col items-center gap-2 text-center">
                    <span class="material-symbols-outlined text-primary text-3xl">groups</span>
                    <span class="text-xs font-label uppercase text-on-surface-variant font-semibold">{{ $field->capacity }} Players Cap</span>
                </div>
                <div class="bg-surface-container-low p-4 rounded-lg flex flex-col items-center gap-2 text-center">
                    <span class="material-symbols-outlined text-primary text-3xl">{{ $field->is_indoor ? 'roofing' : 'wb_sunny' }}</span>
                    <span class="text-xs font-label uppercase text-on-surface-variant font-semibold">{{ $field->is_indoor ? 'Indoor Field' : 'Outdoor Field' }}</span>
                </div>
                <div class="bg-surface-container-low p-4 rounded-lg flex flex-col items-center gap-2 text-center">
                    <span class="material-symbols-outlined text-primary text-3xl">shower</span>
                    <span class="text-xs font-label uppercase text-on-surface-variant font-semibold">Showers Available</span>
                </div>
                <div class="bg-surface-container-low p-4 rounded-lg flex flex-col items-center gap-2 text-center">
                    <span class="material-symbols-outlined text-primary text-3xl">lightbulb</span>
                    <span class="text-xs font-label uppercase text-on-surface-variant font-semibold">Stadium Lights</span>
                </div>
            </div>

            <!-- Details card -->
            <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm">
                <h3 class="font-headline font-bold text-xl mb-4">The Arena Experience</h3>
                <p class="text-on-surface-variant leading-relaxed text-lg font-body">
                    {{ $field->description ?: 'Engineered for peak athletic performance, this court features professional synthetic turf and high-density impact padding. Offers professional-grade traction and ball-roll consistency. Equipped with lighting for 24/7 visibility.' }}
                </p>
                @if($field->features && count($field->features) > 0)
                    <div class="mt-6">
                        <h4 class="font-label font-bold text-xs uppercase tracking-widest mb-3 opacity-60">Field Specifications</h4>
                        <div class="flex gap-2 flex-wrap">
                            @foreach($field->features as $feat)
                                <span class="bg-[#bff5c8]/50 text-secondary-dim px-3 py-1 rounded-full text-xs font-semibold">{{ $feat }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Reviews list -->
            <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm">
                <h3 class="font-headline font-bold text-xl mb-6">Teammate Reviews ({{ $reviews->count() }})</h3>
                <div class="space-y-6">
                    @forelse($reviews as $rev)
                        <div class="border-b border-surface-variant/40 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h5 class="font-headline font-bold text-on-surface">{{ $rev->user->name }}</h5>
                                    <span class="text-xs text-on-surface-variant font-medium">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center text-primary">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="font-bold text-sm ml-1">{{ number_format($rev->rating, 1) }}</span>
                                </div>
                            </div>
                            <p class="text-on-surface-variant font-body leading-relaxed">{{ $rev->review }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">reviews</span>
                            <p class="text-on-surface-variant font-medium">No reviews written for this field yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Booking Column -->
        <aside class="lg:col-span-5 sticky top-28 space-y-6">
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_20px_40px_rgba(13,54,28,0.08)]">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <span class="text-on-surface-variant text-sm block font-label uppercase tracking-widest mb-1">Price per hour</span>
                        <span class="text-4xl font-headline font-black text-primary">${{ number_format($field->price_per_hour, 0) }}</span>
                    </div>
                    <div class="bg-tertiary-container text-on-tertiary-container px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                        20% OFF TODAY
                    </div>
                </div>

                <!-- Calendar Date Strip picker -->
                <div class="mb-8">
                    <h4 class="font-headline font-bold text-lg mb-4">Select Date</h4>
                    <div class="grid grid-cols-7 gap-2">
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $day = \Carbon\Carbon::today()->addDays($i);
                                $isCurrent = ($day->format('Y-m-d') === $selectedDate);
                            @endphp
                            <a href="{{ route('fields.show', [$field->id, 'date' => $day->format('Y-m-d')]) }}" 
                               class="flex flex-col items-center p-2.5 rounded-lg border transition-all cursor-pointer {{ $isCurrent ? 'bg-primary text-white border-primary font-bold shadow-md' : 'bg-surface-container border-transparent hover:bg-surface-variant text-on-surface' }}">
                                <span class="text-[9px] uppercase tracking-wider font-semibold opacity-85">{{ $day->format('D') }}</span>
                                <span class="text-base font-headline font-black mt-1">{{ $day->format('d') }}</span>
                            </a>
                        @endfor
                    </div>
                    <p class="text-xs text-on-surface-variant/70 font-semibold mt-2.5 text-center">Selected Date: {{ $dateObj->format('l, F d, Y') }}</p>
                </div>

                <!-- Time Slot Grid -->
                <div>
                    <h4 class="font-headline font-bold text-lg mb-4">Available Slots (1.5 hrs session)</h4>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($slotsWithAvailability as $slot)
                            @if($slot['is_booked'])
                                <button type="button" class="py-4 px-3 rounded-xl text-sm font-bold text-center bg-surface-dim text-on-surface-variant line-through opacity-50 cursor-not-allowed flex items-center justify-center gap-1.5" disabled>
                                    <span class="material-symbols-outlined text-xs">lock</span>
                                    {{ $slot['label'] }}
                                </button>
                            @else
                                <form action="{{ route('bookings.confirm') }}" method="GET" class="w-full">
                                    <input type="hidden" name="field_id" value="{{ $field->id }}"/>
                                    <input type="hidden" name="date" value="{{ $selectedDate }}"/>
                                    <input type="hidden" name="start_time" value="{{ $slot['start'] }}"/>
                                    <input type="hidden" name="end_time" value="{{ $slot['end'] }}"/>
                                    <button type="submit" class="w-full py-4 px-3 rounded-xl text-sm font-bold text-center border-2 border-transparent bg-secondary-container text-on-secondary-container hover:bg-[#00ec3b] hover:scale-[1.02] active:scale-95 transition-all shadow-sm">
                                        {{ $slot['label'] }}
                                    </button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>

                <p class="text-center text-on-surface-variant text-xs mt-6 font-medium italic">
                    Free cancellation up to 24 hours before kick-off.
                </p>
            </div>

            <!-- Host Highlight -->
            <div class="bg-surface-container-high rounded-xl p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md">
                    <img class="w-full h-full object-cover" alt="Host portrait" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDegK-4yDQDw8UprTtHt093TVvQH0_iIVApyXWw-X9pJEfZzc998rGSqR287JxywroMXyHm14rtx3Da2scLU2Wme6SOc_S29RjJ3Zf1JpHNd5tljtfal7uDjTMIPLU48ZWE6clCRD25akvd_zuiekO7s_SKV_cZdMUd6rpuNuttFPGHIJLokhvvrLAuST_fIeCcQU5dIKHZ9szQaR3l4wKU7s5kl-kW4UpBgSrnLkCqFgKI4sZM_OwWA8bG4qwczygmzzjabFuqbQ"/>
                </div>
                <div>
                    <h5 class="font-headline font-bold text-on-surface">Hosted by Marcus Thorne</h5>
                    <p class="text-sm text-on-surface-variant">Elite Arena Manager • Response time: 5 mins</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@section('scripts')
<script>
function scrollGallery(direction) {
    const scroller = document.getElementById('gallery-scroller');
    if (!scroller) return;
    
    const card = scroller.querySelector('div');
    if (!card) return;
    
    const scrollAmount = (card.offsetWidth + 24) * direction;
    scroller.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
}
</script>
@endsection
