@extends('layouts.app')

@section('title', 'Secure Your Slot')

@section('content')
<div class="max-w-screen-xl mx-auto px-6 py-12 pb-32">
    <!-- Success Notification Area (Contextual) -->
    <div class="mb-10 p-6 bg-secondary-fixed bg-opacity-20 rounded-lg flex items-center gap-4 border-l-8 border-secondary-fixed">
        <div class="bg-secondary-fixed p-3 rounded-full flex items-center justify-center shadow-lg">
            <span class="material-symbols-outlined text-on-secondary-fixed font-bold">check_circle</span>
        </div>
        <div>
            <h3 class="font-headline font-bold text-on-surface">Final Step: Secure Your Slot</h3>
            <p class="text-on-surface-variant text-sm">You are one click away from hitting the pitch. Review your details below.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Summary Details (Bento Grid Style) -->
        <div class="lg:col-span-7 space-y-8">
            <div class="flex flex-col gap-4">
                <span class="font-label text-sm font-bold text-primary tracking-widest uppercase">Summary Details</span>
                <h1 class="font-headline text-5xl font-black text-on-surface tracking-tight leading-none">Review Your<br/><span class="text-primary">Match Day</span></h1>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Field Image Card -->
                <div class="md:col-span-2 relative group overflow-hidden rounded-lg kinetic-tilt shadow-lg aspect-video">
                    @if($field->image_url)
                        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $field->name }}" src="{{ $field->image_url }}"/>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-[#bff5c8] text-primary">
                            <span class="material-symbols-outlined text-7xl">sports_tennis</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 to-transparent flex items-end p-8">
                        <div>
                            <h2 class="font-headline text-3xl font-bold text-surface-container-lowest">{{ $field->name }}</h2>
                            <p class="text-surface-container flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                <span>{{ $field->location }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Date Card -->
                <div class="bg-surface-container-lowest p-8 rounded-lg shadow-[0_10px_30px_rgba(13,54,28,0.04)] flex flex-col justify-between h-48">
                    <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
                    <div>
                        <p class="font-label text-xs text-on-surface-variant uppercase font-bold tracking-widest mb-1">Date Selected</p>
                        <p class="font-headline text-2xl font-bold text-on-surface">{{ \Carbon\Carbon::parse($date)->format('l, F d') }}</p>
                    </div>
                </div>

                <!-- Time Card -->
                <div class="bg-surface-container-lowest p-8 rounded-lg shadow-[0_10px_30px_rgba(13,54,28,0.04)] flex flex-col justify-between h-48">
                    <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">schedule</span>
                    <div>
                        <p class="font-label text-xs text-on-surface-variant uppercase font-bold tracking-widest mb-1">Time Slot</p>
                        <p class="font-headline text-2xl font-bold text-on-surface">{{ \Carbon\Carbon::parse($startTime)->format('H:i') }} - {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout / Confirmation Column -->
        <div class="lg:col-span-5 sticky top-32">
            <div class="bg-surface-container-low rounded-lg p-8 space-y-8 shadow-[0_20px_40px_rgba(13,54,28,0.08)]">
                <h3 class="font-headline text-2xl font-bold text-on-surface border-b border-outline-variant/20 pb-4">Payment Summary</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-on-surface-variant">
                        <span class="font-body font-medium">Court Rental (1.5 hrs)</span>
                        <span class="font-headline font-semibold">${{ number_format($courtRental, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-on-surface-variant">
                        <span class="font-body font-medium">Service Fee</span>
                        <span class="font-headline font-semibold">${{ number_format($serviceFee, 2) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between items-center text-tertiary font-bold italic">
                            <span class="font-body">Flash Discount (10%)</span>
                            <span class="font-headline">-${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="pt-4 border-t border-outline-variant/30 flex justify-between items-baseline">
                        <span class="font-headline text-xl font-bold text-on-surface">Total Due</span>
                        <span class="font-headline text-4xl font-black text-primary">${{ number_format($totalDue, 2) }}</span>
                    </div>
                </div>

                <div class="bg-surface-variant/30 p-4 rounded-lg flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">security</span>
                    <p class="text-xs text-on-surface-variant font-medium">Safe and secure booking guaranteed by raketinAJA Encrypted Checkout.</p>
                </div>

                <!-- Confirmation Form -->
                <form action="{{ route('bookings.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="field_id" value="{{ $field->id }}"/>
                    <input type="hidden" name="date" value="{{ $date }}"/>
                    <input type="hidden" name="start_time" value="{{ $startTime }}"/>
                    <input type="hidden" name="end_time" value="{{ $endTime }}"/>
                    <input type="hidden" name="total_price" value="{{ $totalDue }}"/>
                    
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dim text-white font-headline font-extrabold text-xl py-6 rounded-xl shadow-lg transform transition-all active:scale-95 duration-150 flex items-center justify-center gap-3">
                        Confirm Booking
                        <span class="material-symbols-outlined">rocket_launch</span>
                    </button>
                    
                    <a href="{{ route('fields.show', [$field->id, 'date' => $date]) }}" class="block text-center w-full bg-surface-container-highest/50 hover:bg-surface-container-highest text-on-surface font-body font-bold py-4 rounded-xl transition-colors">
                        Cancel &amp; Change Field
                    </a>
                </form>
            </div>

            <!-- Add Friends Promo -->
            <div class="mt-6 bg-secondary-fixed text-on-secondary-fixed rounded-lg p-6 flex justify-between items-center overflow-hidden relative group shadow-sm">
                <div class="relative z-10">
                    <h4 class="font-headline font-bold text-lg leading-tight">Split the bill?</h4>
                    <p class="text-sm opacity-80 mb-3">Invite 4 teammates now</p>
                    <button class="bg-on-secondary-fixed text-secondary-fixed font-bold px-4 py-2 rounded-full text-xs">Invite Friends</button>
                </div>
                <span class="material-symbols-outlined text-8xl opacity-10 absolute -right-4 -bottom-4 rotate-12 transition-transform group-hover:scale-110">groups</span>
            </div>
        </div>
    </div>
</div>
@endsection
