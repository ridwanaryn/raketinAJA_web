@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-screen-xl mx-auto px-6 py-12 pb-32">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="font-headline font-black text-4xl md:text-5xl tracking-tighter text-on-background mb-2">My Bookings</h1>
            <p class="font-body text-on-surface-variant font-medium">Review your upcoming sessions and match history.</p>
        </div>
        <a href="{{ route('fields.index') }}" class="bg-primary text-on-primary px-8 py-4 rounded-xl font-headline font-bold flex items-center gap-3 shadow-lg hover:scale-95 transition-transform">
            <span class="material-symbols-outlined">add_circle</span>
            Book Another Field
        </a>
    </div>

    <!-- Bookings list -->
    <div class="bg-surface-container-lowest rounded-lg shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b-2 border-surface">
            <h2 class="font-headline font-extrabold text-2xl tracking-tight text-on-surface">Reservations</h2>
        </div>
        
        <div class="divide-y divide-surface">
            @forelse($bookings as $booking)
                @php
                    $isPast = \Carbon\Carbon::parse($booking->booking_date)->isPast();
                @endphp
                <div class="px-8 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-surface-container-low transition-colors">
                    <div class="flex items-start md:items-center gap-6">
                        <div class="w-16 h-16 rounded-xl bg-primary-container/20 flex items-center justify-center shrink-0 text-primary">
                            <span class="material-symbols-outlined text-3xl">
                                @if($booking->field->sport_type === 'padel')
                                    sports_tennis
                                @elseif($booking->field->sport_type === 'tennis')
                                    sports_tennis
                                @else
                                    sports_handball
                                @endif
                            </span>
                        </div>
                        <div>
                            <h3 class="font-body font-extrabold text-xl text-on-surface mb-1">{{ $booking->field->name }}</h3>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-on-surface-variant font-medium">
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">calendar_today</span>{{ $booking->booking_date->format('l, M d, Y') }}</span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">location_on</span>{{ $booking->field->location }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center md:justify-end gap-6 w-full md:w-auto">
                        <div class="md:text-right">
                            <span class="font-headline font-black text-2xl text-on-surface">${{ number_format($booking->total_price, 2) }}</span>
                            <p class="font-label text-[10px] font-bold uppercase tracking-widest mt-1
                               {{ $booking->status === 'confirmed' ? 'text-secondary-dim' : ($booking->status === 'pending' ? 'text-amber-600' : 'text-red-600') }}">
                                {{ $booking->status }}
                            </p>
                        </div>
                        
                        <!-- Review Action or View Review -->
                        <div class="shrink-0 w-full sm:w-auto">
                            @if($isPast)
                                @if($booking->review)
                                    <div class="bg-surface p-3 rounded-lg border border-outline-variant/30 max-w-xs">
                                        <div class="flex items-center text-primary mb-1">
                                            @for($r = 1; $r <= 5; $r++)
                                                <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' {{ $r <= $booking->review->rating ? '1' : '0' }};">star</span>
                                            @endfor
                                            <span class="font-bold text-xs ml-1.5">{{ number_format($booking->review->rating, 1) }}</span>
                                        </div>
                                        <p class="text-xs text-on-surface-variant truncate font-body italic">"{{ $booking->review->review }}"</p>
                                    </div>
                                @else
                                    <button onclick="openReviewModal('{{ $booking->id }}', '{{ $booking->field->name }}')" class="w-full sm:w-auto bg-secondary-container text-on-secondary-container hover:bg-[#00ec3b] font-headline font-bold text-sm px-5 py-3 rounded-xl transition-all">
                                        Leave a Review
                                    </button>
                                @endif
                            @else
                                <span class="text-xs font-bold text-primary bg-primary/10 px-4 py-2.5 rounded-xl border border-primary/20 flex items-center gap-1 w-max">
                                    <span class="material-symbols-outlined text-sm">lock_clock</span> Upcoming
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <span class="material-symbols-outlined text-6xl text-outline mb-4">sports_soccer</span>
                    <h3 class="font-headline text-2xl font-bold text-on-surface">No bookings found</h3>
                    <p class="text-on-surface-variant max-w-md mx-auto mt-2">You haven't locked in any fields yet. Explore fields and make your first reservation today.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Simple Review Modal -->
<div id="review-modal" class="fixed inset-0 z-50 hidden bg-[#001205]/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-lg max-w-md w-full p-8 shadow-2xl border border-white/20 transform transition-all scale-95 duration-200">
        <header class="flex justify-between items-start mb-6">
            <div>
                <h3 class="font-headline font-extrabold text-2xl text-on-surface">Match Review</h3>
                <p id="modal-court-name" class="text-sm font-medium text-on-surface-variant mt-1">Court</p>
            </div>
            <button onclick="closeReviewModal()" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </header>

        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="booking_id" id="modal-booking-id"/>
            
            <!-- Rating star selectors -->
            <div class="space-y-2">
                <label class="font-headline text-xs font-bold uppercase tracking-widest text-on-surface-variant">Your Rating</label>
                <div class="flex gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input class="hidden" type="radio" name="rating" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }} onclick="updateStars({{ $i }})"/>
                            <span class="material-symbols-outlined text-3xl text-outline-variant transition-colors" id="star-icon-{{ $i }}" style="font-variation-settings: 'FILL' 1;">star</span>
                        </label>
                    @endfor
                </div>
            </div>

            <!-- Review text -->
            <div class="space-y-2">
                <label class="font-headline text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="review">Teammate Feedback</label>
                <textarea class="w-full bg-surface-variant/40 border-none rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all font-body text-sm" id="review" name="review" rows="4" placeholder="How did the pitch play? Traction, lighting, hosting experience..." required></textarea>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-dim text-white font-headline font-bold py-4 rounded-xl shadow-lg active:scale-95 transition-all">
                Publish Feedback
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openReviewModal(bookingId, courtName) {
        document.getElementById('modal-booking-id').value = bookingId;
        document.getElementById('modal-court-name').textContent = courtName;
        document.getElementById('review-modal').classList.remove('hidden');
        updateStars(5);
    }
    
    function closeReviewModal() {
        document.getElementById('review-modal').classList.add('hidden');
    }

    function updateStars(rating) {
        for (let i = 1; i <= 5; i++) {
            const icon = document.getElementById('star-icon-' + i);
            if (i <= rating) {
                icon.classList.remove('text-outline-variant');
                icon.classList.add('text-primary');
            } else {
                icon.classList.remove('text-primary');
                icon.classList.add('text-outline-variant');
            }
        }
    }
</script>
@endsection
